<div class="p-4 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-navy-950 font-outfit tracking-tight">Budget Management</h2>
            <p class="text-[11px] text-navy-400 font-bold tracking-widest mt-1 uppercase">Track monthly limits per category</p>
        </div>
        <div class="flex-shrink-0">
            <livewire:ai-budget-planner />
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($budgetData as $item)
            <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                {{-- Category Info --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl flex items-center justify-center text-2xl" style="background-color: {{ $item->category->color }}22;">
                            {{ $item->category->icon }}
                        </div>
                        <div>
                            <h4 class="font-bold text-navy-950">{{ $item->category->name }}</h4>
                            <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest">Monthly Limit</p>
                        </div>
                    </div>
                    <button wire:click="openModal({{ $item->category->id }})" class="p-2 rounded-xl bg-gray-50 text-navy-400 hover:bg-navy-950 hover:text-white transition-all">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                </div>

                {{-- Stats --}}
                <div class="space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-navy-400 font-medium">Spent: <span class="text-navy-950 font-bold">{{ $currency }} {{ number_format($item->spent, 2) }}</span></span>
                        <span class="text-navy-400 font-medium">Budget: <span class="text-navy-950 font-bold">{{ $currency }} {{ number_format($item->budget, 2) }}</span></span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="relative h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="absolute top-0 left-0 h-full transition-all duration-500 rounded-full
                            {{ $item->is_exceeded ? 'bg-red-500' : ($item->is_near_limit ? 'bg-amber-500' : 'bg-gold-600') }}"
                            style="width: {{ min($item->percent, 100) }}%">
                        </div>
                    </div>

                    {{-- Percentage & Status --}}
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold tracking-widest uppercase {{ $item->is_exceeded ? 'text-red-500' : ($item->is_near_limit ? 'text-amber-500' : 'text-gold-700') }}">
                            {{ number_format($item->percent, 1) }}% Used
                        </span>
                        @if($item->is_exceeded)
                            <span class="flex items-center gap-1 text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-widest animate-pulse">
                                <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                Over Limit
                            </span>
                        @elseif($item->is_near_limit)
                            <span class="text-[10px] bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-widest">
                                Near Limit
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Remaining Info --}}
                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-[11px] font-bold uppercase tracking-widest">
                    <span class="text-navy-300">Remaining</span>
                    <span class="{{ $item->remaining < 0 ? 'text-red-500' : 'text-navy-950' }}">
                        {{ $currency }} {{ number_format(abs($item->remaining), 2) }} {{ $item->remaining < 0 ? 'Over' : '' }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-950/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8">
                <h3 class="text-xl font-bold text-navy-950 mb-2">Set Budget</h3>
                <p class="text-xs text-navy-400 font-bold uppercase tracking-widest mb-6">Enter monthly limit for category</p>

                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-navy-400 uppercase tracking-widest mb-2 block">Monthly Amount ({{ $currency }})</label>
                        <input type="number" wire:model="amount" step="0.01" class="w-full bg-gray-50 border-transparent rounded-2xl px-5 py-4 font-bold text-navy-950 focus:ring-2 focus:ring-gold-500 focus:bg-white transition-all text-xl" placeholder="0.00">
                        @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-4">
                        <button wire:click="$set('showModal', false)" class="flex-1 py-4 rounded-2xl border-2 border-gray-100 text-navy-400 font-bold text-sm uppercase tracking-widest hover:bg-gray-50 transition-all">Cancel</button>
                        <button wire:click="saveBudget" class="flex-1 py-4 rounded-2xl bg-gold-700 text-white font-bold text-sm uppercase tracking-widest hover:bg-gold-800 transition-all shadow-lg">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
