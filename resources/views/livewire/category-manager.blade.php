<div class="p-4 sm:p-8" x-data="{}">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Categories</h2>
            <p class="text-[11px] text-navy-400 font-bold tracking-widest mt-1">
                {{ $incomeCount }} Income &nbsp;·&nbsp; {{ $expenseCount }} Expense
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            {{-- Filter tabs --}}
            <div class="flex bg-gray-100 rounded-xl p-1 gap-1">
                @foreach(['all' => 'All', 'income' => 'Income', 'expense' => 'Expense'] as $key => $label)
                    <button wire:click="$set('filter', '{{ $key }}')"
                        class="flex-1 sm:flex-none px-4 py-1.5 rounded-lg text-xs font-bold tracking-widest transition-all
                        {{ $filter === $key ? 'bg-navy-950 text-white shadow' : 'text-navy-400 hover:text-navy-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            {{-- Add buttons --}}
            <button wire:click="openModal('expense')"
                class="flex items-center justify-center gap-2 px-4 py-2.5 bg-navy-950 text-white rounded-xl text-xs font-bold tracking-widest hover:bg-navy-900 transition shadow">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Category
            </button>
        </div>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Category Grid --}}
    @if($categories->isEmpty())
        <div class="text-center py-24">
            <div class="inline-flex p-5 rounded-full bg-gray-100 mb-4">
                <svg class="size-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01M4 21h16a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-navy-400 font-semibold">No categories found.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <div class="relative group bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                    {{-- Icon Circle --}}
                    <div class="size-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"
                         style="background-color: {{ $category->color }}22;">
                        <span>{{ $category->icon }}</span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-navy-950 truncate text-sm">{{ $category->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-widest
                                {{ $category->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ ucfirst($category->type) }}
                            </span>
                            @if($category->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-widest bg-navy-100 text-navy-500">
                                    Default
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions (only for custom categories) --}}
                    @if(!$category->is_default)
                        <div class="absolute top-3 right-3 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="editCategory({{ $category->id }})"
                                class="p-1.5 rounded-lg bg-navy-50 text-navy-700 hover:bg-navy-100 transition">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button wire:click="deleteCategory({{ $category->id }})"
                                wire:confirm="Delete this category?"
                                class="p-1.5 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition">
                                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-init="$el.querySelector('[data-modal]').focus()">
            <div class="absolute inset-0 bg-navy-950/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div data-modal tabindex="-1"
                 class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-navy-950 font-outfit">
                            {{ $editingId ? 'Edit Category' : 'New Category' }}
                        </h3>
                        <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest mt-0.5">Define a custom category</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-2 rounded-xl hover:bg-gray-100 text-gray-400 transition">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-5">
                    {{-- Type Toggle --}}
                    <div>
                        <label class="block text-xs font-bold text-navy-500 uppercase tracking-widest mb-2">Category Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('type', 'expense')"
                                class="py-3 rounded-xl font-bold text-sm uppercase tracking-widest border-2 transition
                                {{ $type === 'expense' ? 'bg-navy-950 border-navy-950 text-white' : 'bg-white border-gray-200 text-navy-400' }}">
                                💸 Expense
                            </button>
                            <button type="button" wire:click="$set('type', 'income')"
                                class="py-3 rounded-xl font-bold text-sm uppercase tracking-widest border-2 transition
                                {{ $type === 'income' ? 'bg-green-600 border-green-600 text-white' : 'bg-white border-gray-200 text-navy-400' }}">
                                💰 Income
                            </button>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-bold text-navy-500 uppercase tracking-widest mb-2">Category Name</label>
                        <input wire:model="name" type="text" placeholder="e.g. Gym Membership"
                            class="w-full bg-white border border-gray-300 text-navy-950 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none transition">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Icon & Color --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-navy-500 tracking-widest mb-2">Icon (Emoji)</label>
                            <input wire:model="icon" type="text" placeholder="🏋️" maxlength="4"
                                class="w-full bg-white border border-gray-300 text-navy-950 rounded-xl px-4 py-3 text-xl text-center focus:ring-2 focus:ring-gold-500 focus:border-gold-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-navy-500 tracking-widest mb-2">Color</label>
                            <div class="flex items-center gap-3 border border-gray-300 rounded-xl px-3 py-2">
                                <input wire:model="color" type="color" class="size-8 rounded-lg border-0 bg-transparent cursor-pointer">
                                <span class="text-sm text-navy-500 font-mono">{{ $color }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="size-12 rounded-2xl flex items-center justify-center text-2xl"
                             style="background-color: {{ $color }}22;">{{ $icon }}</div>
                        <div>
                            <p class="font-bold text-navy-950 text-sm">{{ $name ?: 'Category Name' }}</p>
                            <span class="text-[10px] font-bold uppercase tracking-widest
                                {{ $type === 'income' ? 'text-green-600' : 'text-red-500' }}">{{ $type }}</span>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 pt-2">
                        <button wire:click="$set('showModal', false)"
                            class="flex-1 py-3 rounded-xl border-2 border-gray-200 text-navy-500 font-bold text-sm uppercase tracking-widest hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button wire:click="save"
                            class="flex-1 py-3 rounded-xl bg-gold-700 text-white font-bold text-sm tracking-widest hover:bg-gold-800 transition shadow">
                            {{ $editingId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
