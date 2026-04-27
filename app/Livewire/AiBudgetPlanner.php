<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AiBudgetService;
use App\Models\Category;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class AiBudgetPlanner extends Component
{
    public $income = 0;
    public $oldExpenses = [];
    public $newBudget = [];
    public $advice = '';
    
    public $isGenerated = false;
    public $showModal = false;

    public function generateBudget()
    {
        $aiBudgetService = app(AiBudgetService::class);
        $userId = Auth::id();
        $data = $aiBudgetService->gatherData($userId);
        
        $this->income = $data['income'];
        $this->oldExpenses = $data['expenses'];
        
        // If no expenses, nothing to optimize
        if (empty($this->oldExpenses)) {
            $this->advice = "You don't have any expenses from last month to analyze. Start tracking your spending to get personalized AI budget recommendations!";
            $this->isGenerated = true;
            return;
        }

        $this->newBudget = $aiBudgetService->calculateOptimalBudget($this->income, $this->oldExpenses);
        $this->advice = $aiBudgetService->generateAiAdvice($this->oldExpenses, $this->newBudget);
        
        $this->isGenerated = true;
    }

    public function applyBudget()
    {
        $userId = Auth::id();
        
        foreach ($this->newBudget as $categoryId => $amount) {
            Budget::updateOrCreate(
                ['user_id' => $userId, 'category_id' => $categoryId],
                ['amount' => $amount]
            );
        }

        $this->showModal = false;
        
        $this->dispatch('budgetUpdated');
        
        session()->flash('success', 'AI Budget applied successfully!');
    }

    public function openModal()
    {
        $this->showModal = true;
        if (!$this->isGenerated) {
            $this->generateBudget();
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $categoryIds = array_unique(array_merge(array_keys($this->oldExpenses), array_keys($this->newBudget)));
        $categories = Category::whereIn('id', $categoryIds)->get()->keyBy('id');

        return view('livewire.ai-budget-planner', [
            'categories' => $categories,
            'currency' => Auth::user()->currency ?? '$'
        ]);
    }
}
