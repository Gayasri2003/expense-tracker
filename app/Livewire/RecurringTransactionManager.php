<?php

namespace App\Livewire;

use App\Models\RecurringTransaction;
use App\Models\Category;
use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecurringTransactionManager extends Component
{
    public $recurringTemplates = [];
    public $isModalOpen = false;
    public $editingId = null;

    // Form fields
    public $amount;
    public $category_id;
    public $account_id;
    public $notes;
    public $type = 'expense';
    public $frequency = 'monthly';
    public $start_date;
    public $is_active = true;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'category_id' => 'required|exists:categories,id',
        'account_id' => 'required|exists:accounts,id',
        'notes' => 'nullable|string|max:255',
        'type' => 'required|in:income,expense',
        'frequency' => 'required|in:daily,weekly,monthly,yearly',
        'start_date' => 'required|date',
    ];

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->recurringTemplates = RecurringTransaction::where('user_id', Auth::id())
            ->with(['category', 'account'])
            ->orderBy('is_active', 'desc')
            ->orderBy('next_date', 'asc')
            ->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->category_id = '';
        // Default to first account
        $firstAccount = Account::where('user_id', Auth::id())->first();
        $this->account_id = $firstAccount ? $firstAccount->id : '';
        $this->notes = '';
        $this->type = 'expense';
        $this->frequency = 'monthly';
        $this->start_date = date('Y-m-d');
        $this->is_active = true;
        $this->editingId = null;
    }

    public function edit($id)
    {
        $template = RecurringTransaction::findOrFail($id);
        $this->editingId = $template->id;
        $this->amount = $template->amount;
        $this->category_id = $template->category_id;
        $this->account_id = $template->account_id;
        $this->notes = $template->notes;
        $this->type = $template->type;
        $this->frequency = $template->frequency;
        $this->start_date = $template->start_date->format('Y-m-d');
        $this->is_active = $template->is_active;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $nextDate = Carbon::parse($this->start_date);
        
        // If start date is in the past, we should ideally catch up, 
        // but for simplicity, we'll set next_date to the start_date.
        // The processing logic will handle creation.

        if ($this->editingId) {
            $template = RecurringTransaction::findOrFail($this->editingId);
            $template->update([
                'category_id' => $this->category_id,
                'account_id' => $this->account_id,
                'amount' => $this->amount,
                'notes' => $this->notes,
                'type' => $this->type,
                'frequency' => $this->frequency,
                'start_date' => $this->start_date,
                'next_date' => $this->start_date, // Reset next date to start if modified? Usually yes.
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Recurring transaction updated.');
        } else {
            RecurringTransaction::create([
                'user_id' => Auth::id(),
                'category_id' => $this->category_id,
                'account_id' => $this->account_id,
                'amount' => $this->amount,
                'notes' => $this->notes,
                'type' => $this->type,
                'frequency' => $this->frequency,
                'start_date' => $this->start_date,
                'next_date' => $this->start_date,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Recurring transaction created.');
        }

        $this->loadTemplates();
        $this->closeModal();
    }

    public function toggleActive($id)
    {
        $template = RecurringTransaction::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        $this->loadTemplates();
    }

    public function delete($id)
    {
        RecurringTransaction::findOrFail($id)->delete();
        $this->loadTemplates();
    }

    public function render()
    {
        $categories = Category::forUser()->where('type', $this->type)->orderBy('name')->get();
        $accounts = Account::where('user_id', Auth::id())->orderBy('name')->get();

        return view('livewire.recurring-transaction-manager', [
            'categories' => $categories,
            'accounts' => $accounts,
            'currency' => Auth::user()->currency ?? '$'
        ]);
    }
}
