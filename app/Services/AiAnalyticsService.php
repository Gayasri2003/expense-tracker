<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AiAnalyticsService
{
    public function analyzeSpending($userId)
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        $thisMonthTotal = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->sum('amount');

        $lastMonthTotal = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        $percentageChange = 0;
        $comparisonInsight = "";

        if ($lastMonthTotal > 0) {
            $change = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
            $percentageChange = round(abs($change));
            
            if ($change > 0) {
                $comparisonInsight = "You spent {$percentageChange}% more than last month.";
            } elseif ($change < 0) {
                $comparisonInsight = "You spent {$percentageChange}% less than last month.";
            } else {
                $comparisonInsight = "You spent exactly the same as last month.";
            }
        } elseif ($thisMonthTotal > 0) {
            $comparisonInsight = "You started spending this month. No data for last month to compare.";
        }

        $month2Ago = Carbon::now()->subMonths(2);
        $month3Ago = Carbon::now()->subMonths(3);

        $m1Total = $lastMonthTotal;
        $m2Total = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $month2Ago->month)
            ->whereYear('date', $month2Ago->year)
            ->sum('amount');
        $m3Total = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $month3Ago->month)
            ->whereYear('date', $month3Ago->year)
            ->sum('amount');

        $predictedNextMonth = 0;
        if ($m1Total > 0 || $m2Total > 0 || $m3Total > 0) {
            $predictedNextMonth = round(($m1Total + $m2Total + $m3Total) / 3);
        }

        if ($expenses->isEmpty()) {
            return [
                'category_totals' => [],
                'top_category' => null,
                'insight' => "You haven't recorded any expenses this month. Start tracking to see your spending insights!",
                'comparison_insight' => $comparisonInsight,
                'percentage_change' => $percentageChange,
                'predicted_next_month' => $predictedNextMonth,
                'has_data' => false
            ];
        }

        $categoryTotals = [];
        $topCategoryName = null;
        $topCategoryTotal = 0;

        foreach ($expenses as $index => $expense) {
            $category = Category::find($expense->category_id);
            if ($category) {
                $categoryTotals[$category->name] = [
                    'total' => (float) $expense->total,
                    'color' => $category->color,
                    'icon' => $category->icon
                ];
                if ($index === 0) {
                    $topCategoryName = $category->name;
                    $topCategoryTotal = (float) $expense->total;
                }
            }
        }

        $insight = ucfirst(strtolower($topCategoryName)) . " is your highest spending category this month.";

        return [
            'category_totals' => $categoryTotals,
            'top_category' => strtolower($topCategoryName),
            'insight' => $insight,
            'comparison_insight' => $comparisonInsight,
            'percentage_change' => $percentageChange,
            'predicted_next_month' => $predictedNextMonth,
            'has_data' => true
        ];
    }

    public function handleChatQuery($userId, $query, $currency)
    {
        $query = strtolower(trim($query));
        
        // 1. Detect Timeframe
        $targetMonth = Carbon::now()->month;
        $targetYear = Carbon::now()->year;
        $timeframeLabel = "this month";

        if (preg_match('/\b(last month|previous month)\b/i', $query)) {
            $lastMonth = Carbon::now()->subMonth();
            $targetMonth = $lastMonth->month;
            $targetYear = $lastMonth->year;
            $timeframeLabel = "last month";
        } else {
            $months = [
                'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
                'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
                'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12,
                'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
                'jun' => 6, 'jul' => 7, 'aug' => 8, 'sep' => 9,
                'oct' => 10, 'nov' => 11, 'dec' => 12
            ];

            foreach ($months as $monthName => $monthNum) {
                if (preg_match("/\b{$monthName}\b/i", $query)) {
                    $targetMonth = $monthNum;
                    $timeframeLabel = "in " . ucfirst(strlen($monthName) == 3 ? $monthName : $monthName);
                    if ($targetMonth > Carbon::now()->month) {
                        $targetYear = Carbon::now()->subYear()->year;
                    }
                    break;
                }
            }
        }

        // 2. Detect Account Balances
        $accounts = \App\Models\Account::where('user_id', $userId)->get()->sortByDesc(function($acc) {
            return strlen($acc->name);
        });

        $matchedAccount = null;
        if (preg_match('/\b(balance|have in|account|how much in)\b/i', $query)) {
            foreach ($accounts as $account) {
                $accName = strtolower($account->name);
                if (str_contains($query, $accName)) {
                    $matchedAccount = $account;
                    break;
                }
            }
        }

        if ($matchedAccount) {
            return "Your balance in {$matchedAccount->name} is {$currency} " . number_format($matchedAccount->balance, 2) . ".";
        }

        if (preg_match('/\b(total balance|all accounts|net worth)\b/i', $query)) {
            $totalBalance = \App\Models\Account::where('user_id', $userId)->sum('balance');
            return "Your total balance across all accounts is {$currency} " . number_format($totalBalance, 2) . ".";
        }

        // 3. Detect Category
        // Sort by length descending so "Rent & Housing" matches before "Rent"
        $categories = Category::where('user_id', $userId)->get()->sortByDesc(function($cat) {
            return strlen($cat->name);
        });
        
        $matchedCategory = null;
        
        foreach ($categories as $category) {
            $catName = strtolower($category->name);
            if (str_contains($query, $catName)) {
                $matchedCategory = $category;
                break;
            }
        }

        // 4. Process Query
        
        // Advanced Intent: Highest Spending Category
        if (preg_match('/\b(highest category|top spending|most money|biggest expense|where did i spend the most|top expense)\b/i', $query)) {
            $topCategory = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->select('category_id', DB::raw('SUM(amount) as total'))
                ->groupBy('category_id')
                ->orderByDesc('total')
                ->first();
                
            if ($topCategory) {
                $cat = Category::find($topCategory->category_id);
                return "Your highest spending {$timeframeLabel} was on " . ($cat ? $cat->name : 'Uncategorized') . " with {$currency} " . number_format($topCategory->total, 2) . ".";
            }
            return "You don't have any expenses {$timeframeLabel}.";
        }

        // Advanced Intent: Largest Single Transaction
        if (preg_match('/\b(largest transaction|biggest transaction|highest transaction|most expensive)\b/i', $query)) {
            $largest = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->orderByDesc('amount')
                ->first();
                
            if ($largest) {
                $notes = $largest->notes ? " ('{$largest->notes}')" : "";
                return "Your largest single transaction {$timeframeLabel} was {$currency} " . number_format($largest->amount, 2) . "{$notes}.";
            }
            return "You don't have any transactions {$timeframeLabel}.";
        }

        // Advanced Intent: Savings & Net Income
        if (preg_match('/\b(saved|savings|leftover|remaining|profit|net income)\b/i', $query)) {
            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->sum('amount');
            $expense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->sum('amount');
            
            $saved = $income - $expense;
            if ($saved >= 0) {
                return "You saved {$currency} " . number_format($saved, 2) . " {$timeframeLabel}.";
            } else {
                return "You spent {$currency} " . number_format(abs($saved), 2) . " more than you earned {$timeframeLabel}.";
            }
        }

        // Specific Category Match
        if ($matchedCategory) {
            $spent = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->where('category_id', $matchedCategory->id)
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->sum('amount');

            return "You spent {$currency} " . number_format($spent, 2) . " on {$matchedCategory->name} {$timeframeLabel}.";
        }
        
        // General Income Match
        if (preg_match('/\b(income|earn|earned|earnings|salary|receive|received)\b/i', $query)) {
            $totalIncome = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->sum('amount');
                
            return "Your total income {$timeframeLabel} was {$currency} " . number_format($totalIncome, 2) . ".";
        }
        
        if (preg_match('/\b(total|spend|spent|expense|expenses|cost|costs|outgoings)\b/i', $query)) {
            $total = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $targetMonth)
                ->whereYear('date', $targetYear)
                ->sum('amount');
                
            return "Your total spending {$timeframeLabel} was {$currency} " . number_format($total, 2) . ".";
        }

        return "I'm still learning! You can ask me things like 'How much did I spend on food in March?' or 'What is my total spending last month?'";
    }
}
