<?php

namespace App\Livewire;

use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoanManager extends Component
{
    public $loans = [];
    public $isModalOpen = false;
    public $editingLoanId = null;

    // Form fields
    public $name;
    public $balance = 0; // Remaining loan balance
    public $total_loan_amount = 0;
    public $monthly_interest_amount = 0;
    public $icon = '📜';
    public $color = '#dc2626';

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'balance' => 'required|numeric|min:0',
        'total_loan_amount' => 'required|numeric|min:0',
        'monthly_interest_amount' => 'required|numeric|min:0',
        'icon' => 'required',
        'color' => 'required',
    ];

    public function mount()
    {
        $this->loadLoans();
    }

    public function loadLoans()
    {
        $this->loans = Account::where('user_id', Auth::id())
            ->where('type', 'leasing')
            ->orderBy('name')
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
        $this->name = '';
        $this->balance = 0;
        $this->total_loan_amount = 0;
        $this->monthly_interest_amount = 0;
        $this->icon = '📜';
        $this->color = '#dc2626';
        $this->editingLoanId = null;
    }

    public function edit($id)
    {
        $loan = Account::findOrFail($id);
        $this->editingLoanId = $loan->id;
        $this->name = $loan->name;
        $this->balance = $loan->balance;
        $this->total_loan_amount = $loan->total_loan_amount;
        $this->monthly_interest_amount = $loan->monthly_interest_amount;
        $this->icon = $loan->icon;
        $this->color = $loan->color;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'name' => $this->name,
            'type' => 'leasing',
            'balance' => $this->balance,
            'total_loan_amount' => $this->total_loan_amount,
            'monthly_interest_amount' => $this->monthly_interest_amount,
            'icon' => $this->icon,
            'color' => $this->color,
        ];

        if ($this->editingLoanId) {
            $loan = Account::findOrFail($this->editingLoanId);
            $loan->update($data);
            session()->flash('message', 'Loan updated successfully.');
        } else {
            Account::create($data);
            session()->flash('message', 'Loan created successfully.');
        }

        $this->loadLoans();
        $this->closeModal();
    }

    public function delete($id)
    {
        $loan = Account::findOrFail($id);
        $loan->delete();
        $this->loadLoans();
        session()->flash('message', 'Loan deleted successfully.');
    }

    public function render()
    {
        return view('livewire.loan-manager', [
            'currency' => Auth::user()->currency ?? '$'
        ]);
    }
}
