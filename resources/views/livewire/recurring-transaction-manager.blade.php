<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Recurring Transactions</h2>
            <p class="text-[11px] text-navy-400 font-bold tracking-widest mt-1 uppercase">Automate your repeating bills and income</p>
        </div>
        <button wire:click="openModal" class="w-full sm:w-auto px-6 py-3 bg-navy-950 text-white rounded-xl font-bold hover:bg-navy-900 transition duration-200 shadow-lg flex items-center justify-center group">
            <svg class="size-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Recurring
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl text-sm font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($recurringTemplates as $template)
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-2xl flex items-center justify-center text-2xl" style="background-color: {{ $template->category->color ?? '#eee' }}22;">
                                {{ $template->category->icon ?? '📦' }}
                            </div>
                            <div>
                                <h4 class="font-bold text-navy-950">{{ $template->notes ?: $template->category->name }}</h4>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-bold text-navy-400 uppercase tracking-widest">
                                    {{ $template->frequency }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold {{ $template->type === 'income' ? 'text-green-600' : 'text-navy-950' }}">
                                {{ $template->type === 'income' ? '+' : '-' }} {{ $currency }} {{ number_format($template->amount, 2) }}
                            </span>
                            <button wire:click="toggleActive({{ $template->id }})" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $template->is_active ? 'bg-navy-950' : 'bg-gray-200' }}">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $template->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-4">
                        <div class="p-3 bg-gray-50 rounded-xl col-span-2 sm:col-span-1">
                            <p class="text-[9px] font-bold text-navy-400 uppercase tracking-widest mb-1">Account</p>
                            <p class="text-[11px] font-bold text-navy-950 truncate">{{ $template->account->name }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[9px] font-bold text-navy-400 uppercase tracking-widest mb-1">Next Run</p>
                            <p class="text-[11px] font-bold text-navy-950">{{ $template->next_date->format('M d, Y') }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[9px] font-bold text-navy-400 uppercase tracking-widest mb-1">Status</p>
                            <p class="text-[11px] font-bold {{ $template->is_active ? 'text-green-600' : 'text-red-500' }}">
                                {{ $template->is_active ? 'Active' : 'Paused' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-gray-50">
                    <button wire:click="edit({{ $template->id }})" class="p-2 text-navy-400 hover:text-navy-950 transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button wire:click="delete({{ $template->id }})" wire:confirm="Are you sure you want to stop this automation?" class="p-2 text-navy-400 hover:text-red-600 transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 text-center">
                <div class="size-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-4 text-3xl">🔄</div>
                <h3 class="text-lg font-bold text-navy-950">No automation set up</h3>
                <p class="text-sm text-navy-400 mt-1">Add repeating transactions like Rent or Netflix to let the system handle them automatically.</p>
                <button wire:click="openModal" class="mt-6 text-gold-600 font-bold uppercase tracking-widest text-xs hover:text-gold-700">Set Up First Automation &rarr;</button>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-2xl font-bold text-navy-950 font-outfit tracking-tight">
                        {{ $editingId ? 'Edit Recurring' : 'New Recurring' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-navy-950 transition-colors">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" wire:click="$set('type', 'expense')" class="py-3 rounded-xl font-bold tracking-widest text-xs transition-all {{ $type === 'expense' ? 'bg-navy-950 text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">Expense</button>
                        <button type="button" wire:click="$set('type', 'income')" class="py-3 rounded-xl font-bold tracking-widest text-xs transition-all {{ $type === 'income' ? 'bg-gold-700 text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">Income</button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Amount</label>
                            <input type="number" step="0.01" wire:model="amount" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Frequency</label>
                            <select wire:model="frequency" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Account</label>
                            <select wire:model="account_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                                <option value="">Select Account</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Category</label>
                            <select wire:model="category_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                        </div>
                        <div class="space-y-2 flex flex-col justify-center">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase mb-2">Automation</label>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-navy-400 font-bold uppercase tracking-widest">{{ $is_active ? 'Active' : 'Paused' }}</span>
                                <button type="button" wire:click="$toggle('is_active')" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_active ? 'bg-navy-950' : 'bg-gray-200' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Description</label>
                        <input type="text" wire:model="notes" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950" placeholder="e.g. Monthly House Rent">
                    </div>

                    <div class="pt-4 flex space-x-4">
                        <button type="button" wire:click="closeModal" class="flex-1 py-4 border-2 border-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-50 transition-colors tracking-widest text-xs uppercase">Cancel</button>
                        <button type="submit" class="flex-1 py-4 bg-navy-950 text-white rounded-xl font-bold hover:bg-navy-900 transition-all shadow-xl shadow-navy-900/20 tracking-widest text-xs uppercase">
                            {{ $editingId ? 'Update' : 'Activate' }} Automation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
