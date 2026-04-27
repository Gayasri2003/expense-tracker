<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Expense categories
            ['name' => 'Food & Dining',     'type' => 'expense', 'icon' => '🍽️',  'color' => '#f59e0b'],
            ['name' => 'Transport',          'type' => 'expense', 'icon' => '🚗',  'color' => '#3b82f6'],
            ['name' => 'Bills & Utilities',  'type' => 'expense', 'icon' => '💡',  'color' => '#8b5cf6'],
            ['name' => 'Shopping',           'type' => 'expense', 'icon' => '🛍️',  'color' => '#ec4899'],
            ['name' => 'Health & Medical',   'type' => 'expense', 'icon' => '🏥',  'color' => '#ef4444'],
            ['name' => 'Entertainment',      'type' => 'expense', 'icon' => '🎬',  'color' => '#f97316'],
            ['name' => 'Education',          'type' => 'expense', 'icon' => '📚',  'color' => '#06b6d4'],
            ['name' => 'Rent & Housing',     'type' => 'expense', 'icon' => '🏠',  'color' => '#6366f1'],
            ['name' => 'Other Expense',      'type' => 'expense', 'icon' => '📦',  'color' => '#6b7280'],
            // Income categories
            ['name' => 'Salary',             'type' => 'income',  'icon' => '💼',  'color' => '#10b981'],
            ['name' => 'Freelance',          'type' => 'income',  'icon' => '💻',  'color' => '#14b8a6'],
            ['name' => 'Investments',        'type' => 'income',  'icon' => '📈',  'color' => '#22c55e'],
            ['name' => 'Gift / Bonus',       'type' => 'income',  'icon' => '🎁',  'color' => '#a855f7'],
            ['name' => 'Other Income',       'type' => 'income',  'icon' => '💰',  'color' => '#84cc16'],
        ];

        foreach ($defaults as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name'], 'user_id' => null],
                array_merge($cat, ['is_default' => true, 'user_id' => null])
            );
        }
    }
}
