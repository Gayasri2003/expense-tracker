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

    // Installment fields
    public $isInstallment = false;
    public $installmentMonths = 12;
    public $repaymentAccountId;

    // Leasing fields
    public $isLeasingPayment = false;
    public $leasingAccountId;

    // Filters
    public $filterDateFrom;
    public $filterDateTo;
    public $filterCategoryId = '';
    public $filterAccountId = '';
    public $filterType = 'all';
    public $sortBy = 'date_desc';

    protected function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:255',
            'type' => 'required|in:income,expense',
        ];

        if ($this->isInstallment) {
            $rules['installmentMonths'] = 'required|integer|min:2|max:120';
            $rules['repaymentAccountId'] = 'required|exists:accounts,id';
        }

        if ($this->isLeasingPayment) {
            $rules['leasingAccountId'] = 'required|exists:accounts,id';
        }

        return $rules;
    }

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
        $this->isInstallment = false;
        $this->installmentMonths = 12;
        $this->repaymentAccountId = null;
        $this->isLeasingPayment = false;
        $this->leasingAccountId = null;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->isLeasingPayment) {
                $leasingAccount = Account::findOrFail($this->leasingAccountId);
                $monthlyInterest = $leasingAccount->monthly_interest_amount ?? 0;
                
                $interestPortion = min($this->amount, $monthlyInterest);
                $principalPortion = max(0, $this->amount - $monthlyInterest);

                // 1. Create interest payment record (if any)
                if ($interestPortion > 0) {
                    \App\Models\Transaction::create([
                        'user_id' => Auth::id(),
                        'category_id' => $this->category_id,
                        'account_id' => $this->account_id, // Source account
                        'amount' => $interestPortion,
                        'date' => $this->date,
                        'notes' => 'Interest Payment: ' . ($this->notes ?: $leasingAccount->name),
                        'type' => 'interest_payment',
                    ]);
                }

                // 2. Create principal repayment record (if any)
                if ($principalPortion > 0) {
                    \App\Models\Transaction::create([
                        'user_id' => Auth::id(),
                        'category_id' => $this->category_id,
                        'account_id' => $this->account_id, // Source account
                        'amount' => $principalPortion,
                        'date' => $this->date,
                        'notes' => 'Principal Repayment: ' . ($this->notes ?: $leasingAccount->name),
                        'type' => 'principal_repayment',
                    ]);

                    // Update leasing account balance (decrement liability)
                    $leasingAccount->decrement('balance', $principalPortion);
                }

                // 3. Update source account balance
                $sourceAccount = Account::find($this->account_id);
                if ($sourceAccount) {
                    $sourceAccount->decrement('balance', $this->amount);
                }

            } else {
                // Normal transaction logic
                $actualType = ($this->isInstallment && $this->type === 'expense') ? 'credit_purchase' : $this->type;

                if ($this->editingTransactionId) {
                    $transaction = \App\Models\Transaction::findOrFail($this->editingTransactionId);
                    
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
                        'type' => $actualType,
                    ]);
                } else {
                    $transaction = \App\Models\Transaction::create([
                        'user_id' => Auth::id(),
                        'category_id' => $this->category_id,
                        'account_id' => $this->account_id,
                        'amount' => $this->amount,
                        'date' => $this->date,
                        'notes' => $this->notes,
                        'type' => $actualType,
                    ]);

                    if ($this->isInstallment && $this->type === 'expense') {
                        // Create or Update installment plan
                        $monthlyAmount = round($this->amount / $this->installmentMonths, 2);
                        \App\Models\RecurringTransaction::updateOrCreate(
                            ['transaction_id' => $transaction->id],
                            [
                                'user_id' => Auth::id(),
                                'category_id' => $this->category_id,
                                'account_id' => $this->repaymentAccountId,
                                'credit_card_account_id' => $this->account_id,
                                'amount' => $monthlyAmount,
                                'notes' => 'Installment: ' . $this->notes,
                                'type' => 'expense',
                                'frequency' => 'monthly',
                                'start_date' => $this->date,
                                'next_date' => \Carbon\Carbon::parse($this->date)->addMonth(),
                                'is_active' => true,
                                'is_installment' => true,
                                'total_amount' => $this->amount,
                                'total_months' => $this->installmentMonths,
                                'remaining_months' => $this->installmentMonths,
                            ]
                        );
                    } else {
                        // If it's no longer an installment, delete the plan
                        \App\Models\RecurringTransaction::where('transaction_id', $transaction->id)->delete();
                    }
                }

                // Apply new impact
                $newAccount = Account::find($this->account_id);
                if ($newAccount) {
                    if ($actualType === 'income') {
                        $newAccount->increment('balance', $this->amount);
                    } else {
                        $newAccount->decrement('balance', $this->amount);
                    }
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
        $transaction = \App\Models\Transaction::findOrFail($id);
        $this->editingTransactionId = $id;
        $this->amount = $transaction->amount;
        $this->category_id = $transaction->category_id;
        $this->account_id = $transaction->account_id;
        $this->date = $transaction->date;
        $this->notes = $transaction->notes;
        
        if ($transaction->type === 'credit_purchase') {
            $this->type = 'expense';
            $this->isInstallment = true;
            
            $installment = \App\Models\RecurringTransaction::where('transaction_id', $id)->first();
            if ($installment) {
                $this->installmentMonths = $installment->total_months;
                $this->repaymentAccountId = $installment->account_id;
            }
        } else {
            $this->type = $transaction->type;
            $this->isInstallment = false;
        }

        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $transaction = \App\Models\Transaction::findOrFail($id);
            
            // Reverse impact before deleting
            $account = Account::find($transaction->account_id);
            if ($account) {
                if ($transaction->type === 'income') {
                    $account->decrement('balance', $transaction->amount);
                } else {
                    $account->increment('balance', $transaction->amount);
                }
            }

            // Delete linked installment plan if exists
            \App\Models\RecurringTransaction::where('transaction_id', $id)->delete();
            
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
