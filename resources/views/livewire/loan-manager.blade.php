<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Loans & Leases</h2>
            <p class="text-[11px] text-navy-400 font-bold tracking-widest mt-1 uppercase">Track your liabilities and repayments</p>
        </div>
        <button wire:click="openModal" class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition duration-200 shadow-lg flex items-center justify-center group">
            <svg class="size-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Loan
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl text-sm font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($loans as $loan)
            <div class="relative group h-64 rounded-[2rem] p-8 text-white overflow-hidden shadow-2xl transition-all hover:scale-[1.02]" style="background-color: {{ $loan->color }};">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Leasing / Loan</span>
                            <h3 class="text-xl font-bold font-outfit">{{ $loan->name }}</h3>
                        </div>
                        <div class="size-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl">
                            {{ $loan->icon }}
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Total Loan</span>
                            <span class="font-bold">{{ $currency }} {{ number_format($loan->total_loan_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest opacity-60">Monthly Interest</span>
                            <span class="font-bold">{{ $currency }} {{ number_format($loan->monthly_interest_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Remaining Balance</p>
                                <p class="text-2xl font-bold font-outfit">{{ $currency }} {{ number_format($loan->balance, 2) }}</p>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $loan->id }})" class="p-2 rounded-lg bg-white/20 hover:bg-white/40 transition-colors">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button wire:click="delete({{ $loan->id }})" wire:confirm="Are you sure? This will delete the loan record." class="p-2 rounded-lg bg-red-500/20 hover:bg-red-500/40 transition-colors">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -right-4 -bottom-4 size-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-50 rounded-[2.5rem] border-2 border-dashed border-gray-200 text-center">
                <div class="size-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-4 text-3xl">📜</div>
                <h3 class="text-lg font-bold text-navy-950">No loans found</h3>
                <p class="text-sm text-navy-400 mt-1">Add your loans or leases here to track repayments separately from your assets.</p>
                <button wire:click="openModal" class="mt-6 text-red-600 font-bold uppercase tracking-widest text-xs hover:text-red-700">Add Loan Now &rarr;</button>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                    <h3 class="text-2xl font-bold text-navy-950 font-outfit tracking-tight">
                        {{ $editingLoanId ? 'Edit Loan' : 'New Loan' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-navy-950 transition-colors">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8 space-y-6 overflow-y-auto">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Loan Name</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-sm font-medium text-navy-950" placeholder="e.g. Car Lease, Home Loan">
                        @error('name') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Total Loan Amount</label>
                            <input type="number" step="0.01" wire:model="total_loan_amount" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                            @error('total_loan_amount') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Monthly Interest</label>
                            <input type="number" step="0.01" wire:model="monthly_interest_amount" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                            @error('monthly_interest_amount') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Remaining Balance</label>
                            <input type="number" step="0.01" wire:model="balance" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                            @error('balance') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Icon (Emoji)</label>
                            <input type="text" wire:model="icon" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-xl focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-sm font-medium text-navy-950">
                            @error('icon') <span class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy-900 tracking-widest pl-1 uppercase">Theme Color</label>
                        <div class="flex gap-3">
                            @foreach(['#dc2626', '#b91c1c', '#991b1b', '#0f172a', '#ca8a04', '#16a34a', '#2563eb'] as $colorOption)
                                <button type="button" wire:click="$set('color', '{{ $colorOption }}')" class="size-8 rounded-full border-2 {{ $color === $colorOption ? 'border-navy-950 scale-125' : 'border-transparent' }} transition-all shadow-sm" style="background-color: {{ $colorOption }};"></button>
                            @endforeach
                            <input type="color" wire:model="color" class="size-8 rounded-full border-none p-0 cursor-pointer overflow-hidden">
                        </div>
                    </div>

                    <div class="pt-4 flex space-x-4">
                        <button type="button" wire:click="closeModal" class="flex-1 py-4 border-2 border-gray-100 text-gray-500 rounded-xl font-bold hover:bg-gray-50 transition-colors tracking-widest text-xs uppercase">Cancel</button>
                        <button type="submit" class="flex-1 py-4 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all shadow-xl shadow-red-900/20 tracking-widest text-xs uppercase">
                            {{ $editingLoanId ? 'Update' : 'Create' }} Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
