<?php

namespace App\Services;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecurringTransactionService
{
    public static function process()
    {
        $now = Carbon::now()->startOfDay();

        $templates = RecurringTransaction::where('is_active', true)
            ->where('next_date', '<=', $now)
            ->get();

        foreach ($templates as $template) {
            DB::transaction(function () use ($template, $now) {
                if ($template->is_installment) {
                    // 1. Record installment amount as an expense against cash/bank account
                    Transaction::create([
                        'user_id' => $template->user_id,
                        'category_id' => $template->category_id,
                        'account_id' => $template->account_id, // Repayment account
                        'amount' => $template->amount,
                        'notes' => $template->notes . ' (' . ($template->total_months - $template->remaining_months + 1) . '/' . $template->total_months . ')',
                        'date' => $template->next_date,
                        'type' => 'expense',
                    ]);

                    // Update repayment account balance
                    $repaymentAccount = Account::find($template->account_id);
                    if ($repaymentAccount) {
                        $repaymentAccount->decrement('balance', $template->amount);
                    }

                    // 2. Restore corresponding amount back to the credit card's available limit
                    $creditCardAccount = Account::find($template->credit_card_account_id);
                    if ($creditCardAccount) {
                        $creditCardAccount->increment('balance', $template->amount);
                    }

                    // 3. Update installment record
                    $remaining = $template->remaining_months - 1;
                    $nextDate = Carbon::parse($template->next_date)->addMonth();
                    
                    $template->update([
                        'last_run_date' => $now,
                        'next_date' => $nextDate,
                        'remaining_months' => $remaining,
                        'is_active' => $remaining > 0,
                    ]);
                } else {
                    // Normal recurring transaction
                    Transaction::create([
                        'user_id' => $template->user_id,
                        'category_id' => $template->category_id,
                        'account_id' => $template->account_id,
                        'amount' => $template->amount,
                        'notes' => $template->notes . ' (Auto)',
                        'date' => $template->next_date,
                        'type' => $template->type,
                    ]);

                    $account = Account::find($template->account_id);
                    if ($account) {
                        if ($template->type === 'income') {
                            $account->increment('balance', $template->amount);
                        } else {
                            $account->decrement('balance', $template->amount);
                        }
                    }

                    $nextDate = Carbon::parse($template->next_date);
                    switch ($template->frequency) {
                        case 'daily': $nextDate->addDay(); break;
                        case 'weekly': $nextDate->addWeek(); break;
                        case 'monthly': $nextDate->addMonth(); break;
                        case 'yearly': $nextDate->addYear(); break;
                    }

                    $template->update([
                        'last_run_date' => $now,
                        'next_date' => $nextDate,
                    ]);
                }
            });
        }
    }
}
