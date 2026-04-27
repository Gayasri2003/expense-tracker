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
                // Create the actual transaction
                Transaction::create([
                    'user_id' => $template->user_id,
                    'category_id' => $template->category_id,
                    'account_id' => $template->account_id,
                    'amount' => $template->amount,
                    'notes' => $template->notes . ' (Auto)',
                    'date' => $template->next_date, // Record it on the day it was supposed to run
                    'type' => $template->type,
                ]);

                // Update account balance
                $account = Account::find($template->account_id);
                if ($account) {
                    if ($template->type === 'income') {
                        $account->increment('balance', $template->amount);
                    } else {
                        $account->decrement('balance', $template->amount);
                    }
                }

                // Calculate next run date
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
            });
        }
    }
}
