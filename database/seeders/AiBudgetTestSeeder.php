<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Budget;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AiBudgetTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure User exists
        $user = User::firstOrCreate(
            ['email' => 'test1@gmail.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('Test@123'),
                'currency' => 'Rs',
            ]
        );

        // Update password if user already exists
        $user->update(['password' => Hash::make('Test@123')]);

        // 2. Ensure Account exists
        $account = Account::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Main Bank Account'],
            ['type' => 'bank', 'balance' => 50000, 'icon' => '🏦', 'color' => '#3b82f6']
        );

        // 3. Setup categories
        $categories = [
            ['name' => 'Salary', 'type' => 'income', 'icon' => 'fas fa-money-bill-wave', 'color' => '#10b981'],
            ['name' => 'Food', 'type' => 'expense', 'icon' => 'fas fa-utensils', 'color' => '#f59e0b'],
            ['name' => 'Rent', 'type' => 'expense', 'icon' => 'fas fa-home', 'color' => '#ef4444'],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => 'fas fa-film', 'color' => '#8b5cf6'],
            ['name' => 'Transport', 'type' => 'expense', 'icon' => 'fas fa-car', 'color' => '#3b82f6'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[$cat['name']] = Category::firstOrCreate(
                ['name' => $cat['name'], 'type' => $cat['type'], 'user_id' => $user->id],
                ['icon' => $cat['icon'], 'color' => $cat['color']]
            );
        }

        // 4. Clear existing transactions for last month to prevent duplication
        $lastMonth = Carbon::now()->subMonth();
        Transaction::where('user_id', $user->id)
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->delete();

        // Income last month
        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $createdCategories['Salary']->id,
            'account_id' => $account->id,
            'amount' => 100000,
            'type' => 'income',
            'date' => $lastMonth->copy()->startOfMonth()->addDays(2),
            'notes' => 'Last Month Salary',
        ]);

        // Expenses last month (High food, high entertainment, so AI budget will reduce them)
        // Rent (30%) -> 30000
        // Food (35%) -> 35000  (Over 30%, AI will suggest reducing)
        // Entertainment (20%) -> 20000
        // Transport (10%) -> 10000
        
        $expenses = [
            ['cat' => 'Rent', 'amount' => 30000, 'date' => $lastMonth->copy()->startOfMonth()->addDays(5)],
            ['cat' => 'Food', 'amount' => 35000, 'date' => $lastMonth->copy()->startOfMonth()->addDays(10)],
            ['cat' => 'Entertainment', 'amount' => 20000, 'date' => $lastMonth->copy()->startOfMonth()->addDays(15)],
            ['cat' => 'Transport', 'amount' => 10000, 'date' => $lastMonth->copy()->startOfMonth()->addDays(20)],
        ];

        foreach ($expenses as $exp) {
            Transaction::create([
                'user_id' => $user->id,
                'category_id' => $createdCategories[$exp['cat']]->id,
                'account_id' => $account->id,
                'amount' => $exp['amount'],
                'type' => 'expense',
                'date' => $exp['date'],
                'notes' => $exp['cat'] . ' expense',
            ]);
        }
    }
}
