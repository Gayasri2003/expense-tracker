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
                            <td class="px-6 py-5 text-right font-outfit">
                                <div class="flex flex-col items-end">
                                    <span class="text-lg font-bold {{ 
                                        $transaction->type === 'income' ? 'text-green-600' : 
                                        ($transaction->type === 'credit_purchase' ? 'text-navy-400' : 
                                        ($transaction->type === 'principal_repayment' ? 'text-emerald-600' : 
                                        ($transaction->type === 'interest_payment' ? 'text-red-600' : 'text-navy-950'))) 
                                    }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} {{ Auth::user()->currency }} {{ number_format($transaction->amount, 2) }}
                                    </span>
                                    @if($transaction->type === 'credit_purchase')
                                        <span class="text-[9px] font-bold text-navy-400 uppercase tracking-widest bg-gray-100 px-1.5 py-0.5 rounded mt-1">Credit Purchase</span>
                                    @elseif($transaction->type === 'interest_payment')
                                        <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest bg-red-50 px-1.5 py-0.5 rounded mt-1">Lease Interest</span>
                                    @elseif($transaction->type === 'principal_repayment')
                                        <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 px-1.5 py-0.5 rounded mt-1">Lease Principal</span>
                                    @endif
                                </div>
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
            <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                    <h3 class="text-2xl font-bold text-navy-950 font-outfit tracking-tight">
                        {{ $editingTransactionId ? 'Edit Transaction' : 'New Transaction' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-navy-950 transition-colors">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8 space-y-6 overflow-y-auto custom-scrollbar">
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
                                <input type="number" step="0.01" wire:model.live="amount" class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all font-bold text-navy-950" placeholder="0.00">
                            </div>
                            @error('amount') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1">Account</label>
                            <select wire:model.live="account_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
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
                        <textarea wire:model="notes" rows="2" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-sm font-medium text-navy-950" placeholder="What was this for?"></textarea>
                        @error('notes') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    @if($type === 'expense')
                        @php
                            $selectedAccount = collect($userAccounts)->firstWhere('id', $account_id);
                        @endphp
                        
                        @if($selectedAccount && $selectedAccount->type === 'credit_card')
                            <div class="p-5 bg-navy-50 rounded-2xl border border-navy-100 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-navy-600">
                                            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-navy-950">Installment Plan</h4>
                                            <p class="text-[10px] text-navy-500 uppercase font-bold tracking-widest">Pay in monthly parts</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model.live="isInstallment" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-600"></div>
                                    </label>
                                </div>

                                @if($isInstallment)
                                    <div class="grid grid-cols-2 gap-4 animate-fade-in">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest">Months</label>
                                            <input type="number" wire:model.live="installmentMonths" class="w-full px-3 py-2 bg-white border-transparent rounded-lg text-sm font-bold text-navy-900 focus:ring-1 focus:ring-gold-500" min="2" max="120">
                                            @error('installmentMonths') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest">Repay From</label>
                                            <select wire:model="repaymentAccountId" class="w-full px-3 py-2 bg-white border-transparent rounded-lg text-sm font-medium text-navy-900 focus:ring-1 focus:ring-gold-500">
                                                <option value="">Select Account</option>
                                                @foreach($userAccounts as $acc)
                                                    @if($acc->type !== 'credit_card')
                                                        <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('repaymentAccountId') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-navy-400 font-medium italic">
                                        Estimated monthly payment: <span class="text-navy-950 font-bold">{{ Auth::user()->currency }} {{ number_format($amount / ($installmentMonths ?: 1), 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif

                    {{-- Leasing Payment Section --}}
                    @if($type === 'expense')
                        <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600">
                                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-navy-950">Leasing Payment</h4>
                                        <p class="text-[10px] text-emerald-600 uppercase font-bold tracking-widest">Pay toward a loan or lease</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="isLeasingPayment" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                </label>
                            </div>

                            @if($isLeasingPayment)
                                <div class="space-y-4 animate-fade-in">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Target Leasing Account</label>
                                        <select wire:model.live="leasingAccountId" class="w-full px-4 py-3 bg-white border-transparent rounded-xl text-sm font-medium text-navy-950 focus:ring-2 focus:ring-emerald-500">
                                            <option value="">Select Leasing Account</option>
                                            @foreach($userAccounts as $acc)
                                                @if($acc->type === 'leasing')
                                                    <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('leasingAccountId') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>

                                    @php
                                        $selectedLease = collect($userAccounts)->firstWhere('id', $leasingAccountId);
                                        $interest = 0;
                                        $principal = 0;
                                        if($selectedLease) {
                                            $interest = min($amount ?: 0, $selectedLease->monthly_interest_amount);
                                            $principal = max(0, ($amount ?: 0) - $interest);
                                        }
                                    @endphp

                                    @if($selectedLease)
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="p-3 bg-white rounded-xl shadow-sm">
                                                <p class="text-[9px] font-bold text-navy-400 uppercase tracking-widest">Interest (Expense)</p>
                                                <p class="text-sm font-bold text-red-600">{{ Auth::user()->currency }} {{ number_format($interest, 2) }}</p>
                                            </div>
                                            <div class="p-3 bg-white rounded-xl shadow-sm">
                                                <p class="text-[9px] font-bold text-navy-400 uppercase tracking-widest">Principal (Repayment)</p>
                                                <p class="text-sm font-bold text-emerald-600">{{ Auth::user()->currency }} {{ number_format($principal, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="text-[10px] text-navy-400 font-medium italic">
                                            New Remaining Balance: <span class="text-navy-950 font-bold">{{ Auth::user()->currency }} {{ number_format($selectedLease->balance - $principal, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

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
