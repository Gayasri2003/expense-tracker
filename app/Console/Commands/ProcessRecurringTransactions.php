<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-recurring-transactions';

    protected $description = 'Process all due recurring transactions and installments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing recurring transactions...');
        \App\Services\RecurringTransactionService::process();
        $this->info('Finished processing.');
    }
}
