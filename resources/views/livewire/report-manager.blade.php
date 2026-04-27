<div class="p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Reports & Exports</h2>
            <p class="text-[11px] text-navy-400 font-bold tracking-widest mt-1 uppercase">Generate detailed financial statements</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button wire:click="exportPdf" class="flex-1 sm:flex-none px-5 py-2.5 bg-navy-950 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-navy-900 transition-all flex items-center justify-center gap-2 shadow-lg shadow-navy-950/10">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h1.5m1.5 0H13m-4 4h4m-4 4h4"/></svg>
                PDF
            </button>
            <button wire:click="exportExcel" class="flex-1 sm:flex-none px-5 py-2.5 bg-white border-2 border-navy-950 text-navy-950 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Excel
            </button>
        </div>
    </div>

    {{-- Horizontal Filter Bar --}}
    <div class="bg-gray-50 p-6 rounded-3xl mb-8 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Date From --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">From Date</label>
                <input type="date" wire:model.live="dateFrom" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
            </div>
            {{-- Date To --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">To Date</label>
                <input type="date" wire:model.live="dateTo" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
            </div>
            {{-- Category --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Category</label>
                <select wire:model.live="categoryId" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Type --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Type</label>
                <select wire:model.live="type" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500">
                    <option value="all">All Transactions</option>
                    <option value="income">Income Only</option>
                    <option value="expense">Expenses Only</option>
                </select>
            </div>
            {{-- Account --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Account Filter</label>
                <select wire:model.live="accountId" class="w-full bg-white border-gray-200 rounded-xl text-sm focus:ring-gold-500 focus:border-gold-500 font-medium">
                    <option value="">All Accounts</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->icon }} {{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Include Initial Balance --}}
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest pl-1">Balance Option</label>
                <div style="height: 44px;" class="flex items-center px-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300 transition-colors">
                    <label class="flex items-center cursor-pointer w-full">
                        <input type="checkbox" wire:model.live="includeInitialBalance" style="width: 18px; height: 18px;" class="rounded border-gray-300 text-gold-600 focus:ring-gold-500 cursor-pointer" />
                        <span class="ml-3 text-xs font-bold text-navy-700">Add Initial Balance</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-white rounded-3xl p-4 sm:p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <h3 class="text-lg font-bold text-navy-950">Report Preview</h3>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 sm:justify-end">
                @if($includeInitialBalance && $initialBalance != 0)
                <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-[9px] sm:text-[10px] text-blue-600 font-bold uppercase tracking-widest leading-none mb-1">Initial Balance</p>
                    <p class="text-xs sm:text-sm font-bold text-blue-700 leading-none">{{ number_format($initialBalance, 2) }}</p>
                </div>
                @endif
                <div>
                    <p class="text-[9px] sm:text-[10px] text-navy-400 font-bold uppercase tracking-widest leading-none mb-1">{{ $includeInitialBalance ? 'Closing Balance' : 'Net Cashflow' }}</p>
                    <p class="text-lg sm:text-xl font-bold {{ $total >= 0 ? 'text-green-600' : 'text-red-500' }} leading-none">
                        {{ $total >= 0 ? '+' : '' }}{{ number_format($total, 2) }}
                    </p>
                </div>
                <div class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gold-50 rounded-xl border border-gold-100">
                    <span class="text-[10px] sm:text-xs font-bold text-gold-700 leading-none">{{ $count }} Records</span>
                </div>
            </div>
        </div>

        @if($preview->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                <p class="text-navy-400 font-medium">No data matching your selection.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-gray-100">
                <table class="w-full text-left min-w-[600px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold text-navy-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-navy-400 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-navy-400 uppercase tracking-widest">Notes</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-navy-400 uppercase tracking-widest text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if($includeInitialBalance && $initialBalance != 0)
                            <tr class="bg-blue-50">
                                <td class="px-6 py-4 text-xs font-medium text-navy-600">{{ date('M d, Y', strtotime($dateFrom)) }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-navy-950">—</td>
                                <td class="px-6 py-4 text-xs text-gray-500">Initial Balance</td>
                                <td class="px-6 py-4 text-xs font-bold text-right text-blue-700">
                                    {{ number_format($initialBalance, 2) }}
                                </td>
                            </tr>
                        @endif
                        @foreach($preview as $t)
                            <tr>
                                <td class="px-6 py-4 text-xs font-medium text-navy-600">{{ \Carbon\Carbon::parse($t->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-navy-950">{{ $t->category->icon }} {{ $t->category->name }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $t->notes }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-right {{ $t->type === 'income' ? 'text-green-600' : 'text-navy-950' }}">
                                    {{ $t->type === 'income' ? '+' : '-' }} {{ number_format($t->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($count > 10)
                    <div class="p-4 bg-gray-50 text-center text-[10px] font-bold text-navy-400 uppercase tracking-widest">
                        Previewing top 10 of {{ $count }} records. Export to see full details.
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Info Footer --}}
    <div class="mt-8 bg-navy-950 rounded-3xl p-6 text-white flex flex-col md:flex-row items-center gap-6">
        <div class="p-4 bg-navy-900 rounded-2xl shrink-0">
            <svg class="size-8 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-center md:text-left">
            <h4 class="font-bold text-sm">Professional Exports Ready</h4>
            <p class="text-xs text-navy-400 mt-1">Our reports are optimized for high-quality printing and ISO-compliant spreadsheet imports.</p>
        </div>
    </div>
</div>
