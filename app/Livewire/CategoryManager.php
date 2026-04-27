<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CategoryManager extends Component
{
    public $name = '';
    public $type = 'expense';
    public $icon = '📦';
    public $color = '#6b7280';

    public $editingId = null;
    public $showModal = false;
    public $filter = 'all'; // all | income | expense

    protected $rules = [
        'name'  => 'required|string|max:50',
        'type'  => 'required|in:income,expense',
        'icon'  => 'nullable|string|max:10',
        'color' => 'nullable|string|max:7',
    ];

    public function openModal($type = 'expense')
    {
        $this->reset(['name', 'editingId', 'icon', 'color']);
        $this->type  = $type;
        $this->icon  = '📦';
        $this->color = '#6b7280';
        $this->showModal = true;
    }

    public function editCategory($id)
    {
        $cat = Category::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $cat->id;
        $this->name      = $cat->name;
        $this->type      = $cat->type;
        $this->icon      = $cat->icon;
        $this->color     = $cat->color;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            Category::where('user_id', Auth::id())
                ->where('id', $this->editingId)
                ->update([
                    'name'  => $this->name,
                    'type'  => $this->type,
                    'icon'  => $this->icon,
                    'color' => $this->color,
                ]);
        } else {
            Category::create([
                'user_id' => Auth::id(),
                'name'    => $this->name,
                'type'    => $this->type,
                'icon'    => $this->icon,
                'color'   => $this->color,
                'is_default' => false,
            ]);
        }

        $this->showModal = false;
        $this->reset(['name', 'editingId', 'icon', 'color']);
        session()->flash('success', $this->editingId ? 'Category updated.' : 'Category created.');
    }

    public function deleteCategory($id)
    {
        Category::where('user_id', Auth::id())->where('id', $id)->delete();
    }

    public function render()
    {
        $query = Category::forUser()->orderBy('is_default', 'desc')->orderBy('name');

        if ($this->filter !== 'all') {
            $query->where('type', $this->filter);
        }

        $categories = $query->get();

        $incomeCount  = Category::forUser()->where('type', 'income')->count();
        $expenseCount = Category::forUser()->where('type', 'expense')->count();

        return view('livewire.category-manager', compact('categories', 'incomeCount', 'expenseCount'));
    }
}
