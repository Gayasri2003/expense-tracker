<div class="bg-navy-900 rounded-3xl p-8 shadow-2xl border border-navy-800 relative overflow-hidden group h-full">
    
    <div class="flex items-center justify-between mb-8 relative z-10">
        <div>
            <h3 class="text-xl font-bold text-white">Spending Analytics</h3>
            <p class="text-[10px] text-navy-400 font-bold uppercase tracking-widest mt-1">AI Curated Insights</p>
        </div>
        <div class="p-3 bg-navy-950 rounded-xl group-hover:scale-110 transition-transform shadow-lg">
            <svg class="h-6 w-6 text-gold-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
    </div>

    @if($analytics['has_data'])
        <div class="bg-gold-50/10 border border-gold-500/20 p-5 rounded-2xl mb-4 flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-6 w-6 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-[10px] font-bold text-gold-500 uppercase tracking-widest mb-1">Top Spending Alert</h4>
                <p class="text-sm font-medium text-white">{{ $analytics['insight'] }}</p>
            </div>
        </div>

        @if(!empty($analytics['comparison_insight']))
        <div class="bg-blue-50/10 border border-blue-500/20 p-5 rounded-2xl mb-4 flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">Monthly Comparison</h4>
                <p class="text-sm font-medium text-white">{{ $analytics['comparison_insight'] }}</p>
            </div>
        </div>
        @endif

        @if(!empty($analytics['predicted_next_month']))
        <div class="bg-purple-50/10 border border-purple-500/20 p-5 rounded-2xl mb-8 flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-6 w-6 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-[10px] font-bold text-purple-500 uppercase tracking-widest mb-1">Future Prediction</h4>
                <p class="text-sm font-medium text-white">Based on the last 3 months, you are predicted to spend {{ $currency }} {{ number_format($analytics['predicted_next_month'], 2) }} next month.</p>
            </div>
        </div>
        @endif

        <div class="space-y-4 relative z-10">
            <h4 class="text-[10px] font-bold text-navy-400 uppercase tracking-widest mb-4">Highest Categories</h4>
            @foreach(array_slice($analytics['category_totals'], 0, 3) as $catName => $data)
                <div class="flex items-center justify-between p-3 rounded-xl bg-navy-950/50 border border-navy-800 hover:border-navy-700 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="size-10 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $data['color'] }}22;">
                            {{ $data['icon'] }}
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-white">{{ $catName }}</span>
                            @if($loop->first)
                                <span class="text-[9px] font-bold text-gold-500 uppercase tracking-widest">#1 Highest</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-sm font-bold {{ $loop->first ? 'text-gold-500' : 'text-white' }}">{{ $currency }} {{ number_format($data['total'], 2) }}</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex p-4 rounded-full bg-navy-950 mb-4">
                <svg class="size-8 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-sm font-medium text-navy-400">{{ $analytics['insight'] }}</p>
        </div>
    @endif
</div>
