<?php

namespace App\Livewire;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\On;

class BudgetManager extends Component
{
    public $budgets = []; // category_id => amount
    public $showModal = false;
    public $selectedCategoryId;
    public $amount;

    public function mount()
    {
        $this->loadBudgets();
    }

    #[On('budgetUpdated')]
    public function loadBudgets()
    {
        $existingBudgets = Budget::where('user_id', Auth::id())->pluck('amount', 'category_id')->toArray();
        $this->budgets = $existingBudgets;
    }

    public function openModal($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->amount = $this->budgets[$categoryId] ?? '';
        $this->showModal = true;
    }

    public function saveBudget()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        Budget::updateOrCreate(
            ['user_id' => Auth::id(), 'category_id' => $this->selectedCategoryId],
            ['amount' => $this->amount]
        );

        $this->loadBudgets();
        $this->showModal = false;
        session()->flash('success', 'Budget updated successfully.');
    }

    public function render()
    {
        $categories = Category::forUser()
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $spendingByCategory = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id')
            ->toArray();

        $budgetData = $categories->map(function ($category) use ($spendingByCategory) {
            $budgetAmount = $this->budgets[$category->id] ?? 0;
            $spent = $spendingByCategory[$category->id] ?? 0;
            $remaining = $budgetAmount - $spent;
            $percent = $budgetAmount > 0 ? ($spent / $budgetAmount) * 100 : 0;

            return (object) [
                'category' => $category,
                'budget'   => $budgetAmount,
                'spent'    => $spent,
                'remaining' => $remaining,
                'percent'  => $percent,
                'is_exceeded' => $spent > $budgetAmount && $budgetAmount > 0,
                'is_near_limit' => $percent >= 80 && $percent <= 100,
            ];
        });

        return view('livewire.budget-manager', [
            'budgetData' => $budgetData,
            'currency' => Auth::user()->currency ?? '$'
        ]);
    }
}
