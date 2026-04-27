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

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'type' => 'required',
        'balance' => 'required|numeric',
        'icon' => 'required',
        'color' => 'required',
    ];

    public function mount()
    {
        $this->loadAccounts();
    }

    public function loadAccounts()
    {
        $this->accounts = Account::where('user_id', Auth::id())->orderBy('name')->get();
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
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingAccountId) {
            $account = Account::findOrFail($this->editingAccountId);
            $account->update([
                'name' => $this->name,
                'type' => $this->type,
                'balance' => $this->balance,
                'icon' => $this->icon,
                'color' => $this->color,
            ]);
            session()->flash('message', 'Account updated successfully.');
        } else {
            Account::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'type' => $this->type,
                'balance' => $this->balance,
                'icon' => $this->icon,
                'color' => $this->color,
            ]);
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
