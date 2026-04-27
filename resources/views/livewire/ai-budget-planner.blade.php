<div>
    <!-- Trigger Button -->
    <button wire:click="openModal" class="inline-flex items-center px-4 py-2 bg-gold-600 border border-transparent rounded-xl font-bold text-xs text-navy-950 uppercase tracking-widest hover:bg-gold-500 focus:bg-gold-500 active:bg-gold-700 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        AI Budget Planner
    </button>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-navy-950/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    
                    <!-- Header -->
                    <div class="bg-navy-950 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl leading-6 font-bold text-white flex items-center font-outfit" id="modal-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gold-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                AI Budget Optimizer
                            </h3>
                            <button wire:click="closeModal" class="text-navy-300 hover:text-white transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6 sm:p-8">
                        @if(!$isGenerated)
                            <div class="flex flex-col justify-center items-center py-12">
                                <svg class="animate-spin h-10 w-10 text-gold-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm font-bold text-navy-400 uppercase tracking-widest">Analyzing your financial data...</span>
                            </div>
                        @else
                            <!-- AI Insight Box -->
                            <div class="bg-gold-50 border border-gold-100 p-5 mb-8 rounded-2xl flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-6 w-6 text-gold-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-xs font-bold text-gold-800 uppercase tracking-widest">AI Insight</h3>
                                    <div class="mt-1 text-sm font-medium text-navy-900">
                                        <p>{{ $advice }}</p>
                                    </div>
                                </div>
                            </div>

                            @if(!empty($oldExpenses))
                                <!-- Comparison Table -->
                                <div class="mt-4">
                                    <h4 class="text-sm font-bold text-navy-950 mb-4 uppercase tracking-widest">Proposed Adjustments</h4>
                                    <div class="overflow-hidden shadow-sm border border-gray-100 rounded-2xl">
                                        <table class="min-w-full divide-y divide-gray-100">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-[10px] font-bold text-navy-400 uppercase tracking-widest sm:pl-6">Category</th>
                                                    <th scope="col" class="px-3 py-3.5 text-right text-[10px] font-bold text-navy-400 uppercase tracking-widest">Past Spend</th>
                                                    <th scope="col" class="px-3 py-3.5 text-right text-[10px] font-bold text-gold-600 uppercase tracking-widest">AI Suggested</th>
                                                    <th scope="col" class="px-3 py-3.5 text-center text-[10px] font-bold text-navy-400 uppercase tracking-widest">Change</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50 bg-white">
                                                @foreach($categories as $categoryId => $category)
                                                    @php
                                                        $old = $oldExpenses[$categoryId] ?? 0;
                                                        $new = $newBudget[$categoryId] ?? 0;
                                                        $diff = $new - $old;
                                                    @endphp
                                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                                            <div class="flex items-center">
                                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-navy-950 text-2xl mr-3" style="background-color: {{ $category->color }}22;">
                                                                    {{ $category->icon ?? '🏷️' }}
                                                                </div>
                                                                <span class="font-bold text-navy-950">{{ $category->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-navy-400 text-right">
                                                            {{ $currency }} {{ number_format($old, 2) }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-gold-600 text-right">
                                                            {{ $currency }} {{ number_format($new, 2) }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                                            @if($diff > 0)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-green-50 text-green-700 uppercase tracking-wider">
                                                                    <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    + {{ $currency }} {{ number_format($diff, 2) }}
                                                                </span>
                                                            @elseif($diff < 0)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-red-700 uppercase tracking-wider">
                                                                    <svg class="-ml-0.5 mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    - {{ $currency }} {{ number_format(abs($diff), 2) }}
                                                                </span>
                                                            @else
                                                                <span class="text-navy-300 font-bold">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        @if($isGenerated && !empty($oldExpenses))
                            <button wire:click="applyBudget" type="button" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent px-6 py-3 bg-gold-600 text-xs font-bold text-navy-950 uppercase tracking-widest hover:bg-gold-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold-500 sm:ml-3 sm:w-auto shadow-sm transition">
                                Apply AI Budget
                            </button>
                        @endif
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center items-center rounded-xl border border-gray-200 px-6 py-3 bg-white text-xs font-bold text-navy-400 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-500 sm:mt-0 sm:ml-3 sm:w-auto transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
