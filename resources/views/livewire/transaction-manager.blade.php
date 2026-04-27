<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Recent Transactions</h2>
            <p class="text-[10px] text-navy-400 font-bold tracking-widest mt-0.5 uppercase">Track and manage your spending history</p>
        </div>
        <button wire:click="openModal" class="w-full sm:w-auto px-6 py-3 bg-navy-950 text-white rounded-xl font-bold hover:bg-navy-900 transition duration-200 shadow-lg flex items-center justify-center group">
            <svg class="size-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Transaction
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-gray-50 p-6 rounded-3xl mb-8 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">From Date</label>
                <input type="date" wire:model.live="filterDateFrom" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">To Date</label>
                <input type="date" wire:model.live="filterDateTo" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Category</label>
                <select wire:model.live="filterCategoryId" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="">All Categories</option>
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Account</label>
                <select wire:model.live="filterAccountId" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="">All Accounts</option>
                    @foreach($userAccounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Type</label>
                <select wire:model.live="filterType" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="all">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Sort By</label>
                <select wire:model.live="sortBy" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="date_desc">Latest First</option>
                    <option value="date_asc">Oldest First</option>
                    <option value="amount_desc">Highest Amount</option>
                    <option value="amount_asc">Lowest Amount</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button wire:click="resetFilters" class="text-xs font-bold text-navy-400 hover:text-navy-700 uppercase tracking-widest transition-colors">
                Clear Filters
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-medium rounded-r-lg shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Transactions List -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-navy-50/50 text-navy-900 text-xs font-bold tracking-widest">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Account</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Notes</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-5 text-navy-600 font-medium">{{ \Carbon\Carbon::parse($transaction->date)->format('M d, Y') }}</td>
                            <td class="px-6 py-5">
                                <span class="flex items-center gap-2">
                                    <span class="size-2 rounded-full" style="background-color: {{ $transaction->account->color ?? '#eee' }}"></span>
                                    <span class="text-xs font-bold text-navy-900">{{ $transaction->account->name ?? 'Deleted Account' }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-navy-700 text-xs font-bold">
                                    {{ $transaction->category->icon ?? '📦' }} {{ $transaction->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-gray-500 truncate max-w-xs">{{ $transaction->notes }}</td>
                            <td class="px-6 py-5 text-right font-outfit text-lg font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-navy-950' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} {{ Auth::user()->currency }} {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $transaction->id }})" class="p-2 text-navy-400 hover:text-gold-600 transition-colors">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button wire:click="delete({{ $transaction->id }})" wire:confirm="Are you sure you want to delete this transaction?" class="p-2 text-navy-400 hover:text-red-600 transition-colors">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">No transactions found matching your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-2xl font-bold text-navy-950 font-outfit tracking-tight">
                        {{ $editingTransactionId ? 'Edit Transaction' : 'New Transaction' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-navy-950 transition-colors">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" wire:click="$set('type', 'expense')" class="py-3 rounded-xl font-bold tracking-widest text-xs transition-all {{ $type === 'expense' ? 'bg-navy-950 text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            Expense
                        </button>
                        <button type="button" wire:click="$set('type', 'income')" class="py-3 rounded-xl font-bold tracking-widest text-xs transition-all {{ $type === 'income' ? 'bg-gold-700 text-white shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            Income
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">{{ Auth::user()->currency }}</span>
                                <input type="number" step="0.01" wire:model="amount" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all font-bold text-navy-950" placeholder="0.00">
                            </div>
                            @error('amount') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Account</label>
                            <select wire:model="account_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                                <option value="">Select Account</option>
                                @foreach($userAccounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                                @endforeach
                            </select>
                            @error('account_id') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Category</label>
                            <select wire:model="category_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                                <option value="">Select Category</option>
                                @foreach($modalCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->icon }} {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Date</label>
                            <input type="date" wire:model="date" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                            @error('date') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Notes</label>
                        <textarea wire:model="notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950" placeholder="What was this for?"></textarea>
                        @error('notes') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex space-x-4">
                        <button type="button" wire:click="closeModal" class="flex-1 py-3 border-2 border-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-50 transition-colors tracking-widest text-xs uppercase">Cancel</button>
                        <button type="submit" class="flex-1 py-3 bg-navy-950 text-white rounded-xl font-bold hover:bg-navy-900 transition-all shadow-xl shadow-navy-900/20 tracking-widest text-xs uppercase">
                            {{ $editingTransactionId ? 'Update' : 'Add' }} Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
