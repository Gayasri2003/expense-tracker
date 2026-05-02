<?php

namespace App\Livewire;

use App\Models\Account;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AccountManager extends Component
{
    public $accounts = [];
    public $isModalOpen = false;
    public $editingAccountId = null;

    // Form fields
    public $name;
    public $type = 'cash';
    public $balance = 0;
    public $icon = '💰';
    public $color = '#0f172a';
    public $total_loan_amount = 0;
    public $monthly_interest_amount = 0;

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:50',
            'type' => 'required',
            'balance' => 'required|numeric',
            'icon' => 'required',
            'color' => 'required',
        ];

        if ($this->type === 'leasing') {
            $rules['total_loan_amount'] = 'required|numeric|min:0';
            $rules['monthly_interest_amount'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function mount()
    {
        $this->loadAccounts();
    }

    public function loadAccounts()
    {
        $this->accounts = Account::where('user_id', Auth::id())
            ->where('type', '!=', 'leasing')
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
        $this->type = 'cash';
        $this->balance = 0;
        $this->icon = '💰';
        $this->color = '#0f172a';
        $this->total_loan_amount = 0;
        $this->monthly_interest_amount = 0;
        $this->editingAccountId = null;
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $this->editingAccountId = $account->id;
        $this->name = $account->name;
        $this->type = $account->type;
        $this->balance = $account->balance;
        $this->icon = $account->icon;
        $this->color = $account->color;
        $this->total_loan_amount = $account->total_loan_amount ?? 0;
        $this->monthly_interest_amount = $account->monthly_interest_amount ?? 0;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'name' => $this->name,
            'type' => $this->type,
            'balance' => $this->balance,
            'icon' => $this->icon,
            'color' => $this->color,
            'total_loan_amount' => $this->type === 'leasing' ? $this->total_loan_amount : null,
            'monthly_interest_amount' => $this->type === 'leasing' ? $this->monthly_interest_amount : null,
        ];

        if ($this->editingAccountId) {
            $account = Account::findOrFail($this->editingAccountId);
            $account->update($data);
            session()->flash('message', 'Account updated successfully.');
        } else {
            Account::create($data);
            session()->flash('message', 'Account created successfully.');
        }

        $this->loadAccounts();
        $this->closeModal();
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        $this->loadAccounts();
        session()->flash('message', 'Account deleted successfully.');
    }

    public function render()
    {
        return view('livewire.account-manager', [
            'currency' => Auth::user()->currency ?? '$'
        ]);
    }
}
