<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Budget;
use App\Models\Account;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\RecurringTransactionService;

class DashboardStats extends Component
{
    public $totalBalance = 0;
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $currency = '$';
    public $recentTransactions = [];
    public $months = [];
    public $incomeData = [];
    public $expenseData = [];
    public $budgetAlerts = [];
    public $userAccounts = [];

    #[On('transactionUpdated')]
    public function refreshStats()
    {
        $this->calculateStats();
    }

    public function mount()
    {
        $rawCurrency = Auth::user()->currency ?? '$';

        $codeToSymbol = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'JPY' => '¥',
            'INR' => '₹', 'LKR' => 'Rs', 'AUD' => 'A$', 'CAD' => 'C$',
            'CHF' => 'Fr', 'CNY' => '¥',
        ];

        $this->currency = $codeToSymbol[$rawCurrency] ?? $rawCurrency;
        
        // Auto-process any pending recurring transactions
        RecurringTransactionService::process();

        $this->calculateStats();
    }

    public function calculateStats()
    {
        $allTransactions = Transaction::where('user_id', Auth::id())->get();

        $this->totalIncome = $allTransactions->where('type', 'income')->sum('amount');
        $this->totalExpenses = $allTransactions->where('type', 'expense')->sum('amount');
        
        // Fetch accounts and calculate actual combined balance
        $this->userAccounts = Account::where('user_id', Auth::id())->orderBy('balance', 'desc')->get();
        $this->totalBalance = $this->userAccounts->sum('balance');

        $this->recentTransactions = Transaction::where('user_id', Auth::id())
            ->with(['category', 'account'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Budget Alerts
        $this->budgetAlerts = [];
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $budgets = Budget::where('user_id', Auth::id())->with('category')->get();
        $monthlySpending = Transaction::where('user_id', Auth::id())
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        foreach ($budgets as $budget) {
            $spent = $monthlySpending[$budget->category_id] ?? 0;
            if ($spent > $budget->amount) {
                $this->budgetAlerts[] = [
                    'category' => $budget->category->name,
                    'icon'     => $budget->category->icon,
                    'spent'    => $spent,
                    'budget'   => $budget->amount,
                    'over'     => $spent - $budget->amount
                ];
            }
        }

        // Prepare data for the 3-month chart
        $this->months = [];
        $this->incomeData = [];
        $this->expenseData = [];

        for ($i = 2; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $this->months[] = $monthName;

            $monthIncome = Transaction::where('user_id', Auth::id())
                ->where('type', 'income')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $monthExpense = Transaction::where('user_id', Auth::id())
                ->where('type', 'expense')
                ->whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $this->incomeData[] = $monthIncome;
            $this->expenseData[] = $monthExpense;
        }
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
