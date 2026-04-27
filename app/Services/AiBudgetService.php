<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AiBudgetService
{
    /**
     * Gather previous month's income and expenses grouped by category
     */
    public function gatherData($userId)
    {
        $lastMonth = Carbon::now()->subMonth();

        // Get total income for last month
        $income = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        // Get expenses grouped by category
        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get()
            ->pluck('total', 'category_id')
            ->toArray();

        return [
            'income' => (float) $income,
            'expenses' => $expenses,
        ];
    }

    /**
     * Calculate optimal budget
     */
    public function calculateOptimalBudget($income, $expenses, $savingsGoalPercent = 20)
    {
        $totalExpenses = array_sum($expenses);
        
        // If no income, fallback to total expenses to avoid division by zero or negative budgets
        if ($income <= 0) {
            $income = $totalExpenses > 0 ? $totalExpenses : 1000;
        }

        $savings = $income * ($savingsGoalPercent / 100);
        $availableForBudget = $income - $savings;

        $newBudget = [];
        $cappedCategories = [];
        $remainingAvailable = $availableForBudget;

        // Step 1: Adjust High Categories
        foreach ($expenses as $categoryId => $amount) {
            $percentOfIncome = ($amount / $income) * 100;
            if ($percentOfIncome > 30) {
                $cappedAmount = $income * 0.30;
                $newBudget[$categoryId] = $cappedAmount;
                $cappedCategories[] = $categoryId;
                $remainingAvailable -= $cappedAmount;
            }
        }

        // Step 2: Allocate Remaining Proportionally
        $nonCappedTotal = 0;
        foreach ($expenses as $categoryId => $amount) {
            if (!in_array($categoryId, $cappedCategories)) {
                $nonCappedTotal += $amount;
            }
        }

        foreach ($expenses as $categoryId => $amount) {
            if (!in_array($categoryId, $cappedCategories)) {
                if ($nonCappedTotal > 0) {
                    $proportion = $amount / $nonCappedTotal;
                    $allocatedAmount = $remainingAvailable * $proportion;
                    $newBudget[$categoryId] = $allocatedAmount;
                } else {
                    $newBudget[$categoryId] = 0;
                }
            }
        }

        return $newBudget;
    }

    /**
     * Generate advice
     */
    public function generateAiAdvice($oldExpenses, $newBudget)
    {
        $reduceList = [];

        foreach ($oldExpenses as $categoryId => $oldAmount) {
            $newAmount = $newBudget[$categoryId] ?? 0;
            if ($oldAmount > $newAmount) {
                $category = Category::find($categoryId);
                if ($category) {
                    $reduceList[] = strtolower($category->name);
                }
            }
        }

        if (empty($reduceList)) {
            return "Your spending looks well-balanced! Keep up the good habits to comfortably reach your savings goals. We've optimized your budget to maintain this momentum.";
        }

        if (count($reduceList) > 1) {
            $last = array_pop($reduceList);
            $reduceString = implode(', ', $reduceList) . ' and ' . $last;
        } else {
            $reduceString = $reduceList[0];
        }

        return "To hit your 20% savings goal, try reducing your spending in {$reduceString}. The AI has adjusted these category limits automatically to optimize your wealth growth.";
    }
}
