<div>
    @if(count($budgetAlerts) > 0)
        <div class="mb-8 space-y-3">
            @foreach($budgetAlerts as $alert)
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center justify-between animate-pulse">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-red-100 rounded-lg text-xl">{{ $alert['icon'] }}</div>
                        <div>
                            <h4 class="text-sm font-bold text-red-900">Budget Exceeded: {{ $alert['category'] }}</h4>
                            <p class="text-[10px] text-red-600 font-bold uppercase tracking-widest">You are {{ $currency }} {{ number_format($alert['over'], 2) }} over your monthly limit</p>
                        </div>
                    </div>
                    <a href="{{ route('budgets') }}" class="text-xs font-bold text-red-700 hover:underline uppercase tracking-widest">Adjust Budget</a>
                </div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Balance Card -->
        <div class="bg-navy-900 rounded-3xl p-6 sm:p-8 shadow-xl border border-navy-800 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="size-24 text-gold-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                </svg>
            </div>
            <h3 class="text-navy-300 text-xs sm:text-sm font-medium uppercase tracking-wider">Total Balance</h3>
            <p class="text-xl sm:text-2xl font-bold text-white mt-2">{{ $currency }} {{ number_format($totalBalance, 2) }}</p>
            <div class="mt-4 flex items-center text-[10px] sm:text-xs">
                <span class="text-gold-500 font-semibold">Available Funds</span>
            </div>
        </div>

        <!-- Total Income Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 relative overflow-hidden">
            <h3 class="text-gray-500 text-xs sm:text-sm font-medium uppercase tracking-wider">Total Income</h3>
            <p class="text-xl sm:text-2xl font-bold text-navy-950 mt-2">{{ $currency }} {{ number_format($totalIncome, 2) }}</p>
            <div class="mt-4 flex items-center text-[10px] sm:text-xs text-green-600">
                <svg class="size-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                <span>Lifetime Earnings</span>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div class="bg-gold-700 rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
             <div class="absolute top-0 right-0 p-4 opacity-20">
                <svg class="size-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13H5v-2h14v2z"/>
                </svg>
            </div>
            <h3 class="text-gold-100 text-xs sm:text-sm font-medium uppercase tracking-wider">Total Expenses</h3>
            <p class="text-xl sm:text-2xl font-bold text-white mt-2">{{ $currency }} {{ number_format($totalExpenses, 2) }}</p>
            <div class="mt-4 flex items-center text-[10px] sm:text-xs text-white/80">
                <svg class="size-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <span>Lifetime Spending</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Accounts Summary -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 lg:col-span-1">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-navy-950">Accounts Summary</h3>
                <a href="{{ route('accounts') }}" class="text-xs font-bold text-gold-600 hover:text-gold-700 uppercase tracking-widest">Manage</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                @forelse($userAccounts as $account)
                    <div class="p-4 rounded-2xl border border-gray-50 bg-gray-50/30 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="size-8 rounded-lg flex items-center justify-center text-lg" style="background-color: {{ $account->color }}22;">
                                {{ $account->icon }}
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-navy-400 uppercase tracking-widest">{{ $account->name }}</p>
                                <p class="text-sm font-bold text-navy-950">{{ $currency }} {{ number_format($account->balance, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 text-center py-4">
                        <p class="text-xs text-navy-400">No accounts found. <a href="{{ route('accounts') }}" class="text-gold-600 underline">Add one</a></p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- AI Budget Planner -->
        <div class="bg-navy-900 rounded-3xl p-8 shadow-sm border border-navy-800 flex flex-col items-center justify-center text-center relative overflow-hidden group lg:col-span-1">
            <h3 class="text-xl font-bold text-white mb-2">Smart Budget AI</h3>
            <p class="text-xs text-navy-400 font-medium mb-6">Optimize your monthly spending automatically based on your past habits.</p>
            <livewire:ai-budget-planner />
        </div>

        <!-- Monthly Summary Chart -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-navy-950">Monthly Summary</h3>
                <span class="text-sm text-gray-500">Last 3 Months</span>
            </div>
            <div class="h-48">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <livewire:ai-spending-analytics />
        </div>
        <div class="lg:col-span-2">
            <div class="bg-navy-950 rounded-3xl p-8 shadow-2xl h-full">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-white">Recent Transactions</h3>
                    <a href="{{ route('transactions') }}" class="text-sm text-gold-500 hover:text-gold-400 font-semibold transition-colors">View All</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-center justify-between p-3 sm:p-4 rounded-2xl bg-navy-900 border border-navy-800 hover:border-navy-700 transition-all gap-3 min-w-0">
                            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                                <div class="p-2 sm:p-3 rounded-xl flex-shrink-0 {{ $transaction->type == 'income' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500' }}">
                                    @if($transaction->type == 'income')
                                        <svg class="size-5 sm:size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    @else
                                        <svg class="size-5 sm:size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white font-semibold text-sm sm:text-base truncate">{{ $transaction->notes ?? ($transaction->category->name ?? 'Transaction') }}</p>
                                    <p class="text-navy-400 text-[9px] sm:text-[10px] uppercase tracking-widest font-bold truncate">
                                        {{ \Carbon\Carbon::parse($transaction->date)->format('M d') }} • {{ $transaction->account->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <p class="font-bold text-sm sm:text-base flex-shrink-0 {{ $transaction->type == 'income' ? 'text-green-500' : 'text-white' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }} {{ $currency }} {{ number_format($transaction->amount, 2) }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <div class="inline-flex p-4 rounded-full bg-navy-900 mb-4">
                                <svg class="size-8 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-navy-400">No transactions recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Income',
                            data: @json($incomeData),
                            backgroundColor: '#ca8a04', // gold-600
                            borderRadius: 6,
                        },
                        {
                            label: 'Expenses',
                            data: @json($expenseData),
                            backgroundColor: '#0f172a', // navy-950
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Inter',
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>
