<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionManager extends Component
{
    use WithPagination;

    // Form fields
    public $amount;
    public $category_id;
    public $account_id;
    public $date;
    public $notes;
    public $type = 'expense';
    public $editingTransactionId = null;
    public $isModalOpen = false;

    // Filters
    public $filterDateFrom;
    public $filterDateTo;
    public $filterCategoryId = '';
    public $filterAccountId = '';
    public $filterType = 'all';
    public $sortBy = 'date_desc';

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'category_id' => 'required|exists:categories,id',
        'account_id' => 'required|exists:accounts,id',
        'date' => 'required|date',
        'notes' => 'nullable|string|max:255',
        'type' => 'required|in:income,expense',
    ];

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function updating($property)
    {
        if (in_array($property, ['filterDateFrom', 'filterDateTo', 'filterCategoryId', 'filterAccountId', 'filterType', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function openModal()
    {
        $this->resetInputFields();
        // Default to first account if available
        $firstAccount = Account::where('user_id', Auth::id())->first();
        if ($firstAccount) {
            $this->account_id = $firstAccount->id;
        }
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->amount = '';
        $this->category_id = '';
        $this->account_id = '';
        $this->date = date('Y-m-d');
        $this->notes = '';
        $this->type = 'expense';
        $this->editingTransactionId = null;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->editingTransactionId) {
                $transaction = Transaction::findOrFail($this->editingTransactionId);
                
                // Reverse old impact
                $oldAccount = Account::find($transaction->account_id);
                if ($oldAccount) {
                    if ($transaction->type === 'income') {
                        $oldAccount->decrement('balance', $transaction->amount);
                    } else {
                        $oldAccount->increment('balance', $transaction->amount);
                    }
                }

                $transaction->update([
                    'category_id' => $this->category_id,
                    'account_id' => $this->account_id,
                    'amount' => $this->amount,
                    'date' => $this->date,
                    'notes' => $this->notes,
                    'type' => $this->type,
                ]);
            } else {
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'category_id' => $this->category_id,
                    'account_id' => $this->account_id,
                    'amount' => $this->amount,
                    'date' => $this->date,
                    'notes' => $this->notes,
                    'type' => $this->type,
                ]);
            }

            // Apply new impact
            $newAccount = Account::find($this->account_id);
            if ($newAccount) {
                if ($this->type === 'income') {
                    $newAccount->increment('balance', $this->amount);
                } else {
                    $newAccount->decrement('balance', $this->amount);
                }
            }
        });

        session()->flash('message', $this->editingTransactionId ? 'Transaction Updated Successfully.' : 'Transaction Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
        $this->dispatch('transactionUpdated');
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->editingTransactionId = $id;
        $this->amount = $transaction->amount;
        $this->category_id = $transaction->category_id;
        $this->account_id = $transaction->account_id;
        $this->date = $transaction->date;
        $this->notes = $transaction->notes;
        $this->type = $transaction->type;

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $transaction = Transaction::findOrFail($id);
            
            // Reverse impact before deleting
            $account = Account::find($transaction->account_id);
            if ($account) {
                if ($transaction->type === 'income') {
                    $account->decrement('balance', $transaction->amount);
                } else {
                    $account->increment('balance', $transaction->amount);
                }
            }
            
            $transaction->delete();
        });

        session()->flash('message', 'Transaction Deleted Successfully.');
        $this->dispatch('transactionUpdated');
    }

    public function resetFilters()
    {
        $this->reset(['filterDateFrom', 'filterDateTo', 'filterCategoryId', 'filterAccountId', 'filterType', 'sortBy']);
    }

    public function render()
    {
        $query = Transaction::where('user_id', Auth::id())->with(['category', 'account']);

        // Apply Filters
        if ($this->filterDateFrom) { $query->where('date', '>=', $this->filterDateFrom); }
        if ($this->filterDateTo) { $query->where('date', '<=', $this->filterDateTo); }
        if ($this->filterCategoryId) { $query->where('category_id', $this->filterCategoryId); }
        if ($this->filterAccountId) { $query->where('account_id', $this->filterAccountId); }
        if ($this->filterType !== 'all') { $query->where('type', $this->filterType); }

        // Apply Sorting
        switch ($this->sortBy) {
            case 'date_asc': $query->orderBy('date', 'asc'); break;
            case 'amount_desc': $query->orderBy('amount', 'desc'); break;
            case 'amount_asc': $query->orderBy('amount', 'asc'); break;
            case 'date_desc': default: $query->orderBy('date', 'desc'); break;
        }

        $transactions = $query->paginate(10);
        $allCategories = Category::forUser()->orderBy('is_default', 'desc')->orderBy('name')->get();
        $modalCategories = Category::forUser()->where('type', $this->type)->orderBy('is_default', 'desc')->orderBy('name')->get();
        $userAccounts = Account::where('user_id', Auth::id())->orderBy('name')->get();

        return view('livewire.transaction-manager', [
            'transactions' => $transactions,
            'allCategories' => $allCategories,
            'modalCategories' => $modalCategories,
            'userAccounts' => $userAccounts,
        ]);
    }
}
