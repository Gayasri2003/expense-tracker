<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'account_id',
        'amount',
        'notes',
        'type',
        'frequency',
        'start_date',
        'next_date',
        'last_run_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_date' => 'date',
        'last_run_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
