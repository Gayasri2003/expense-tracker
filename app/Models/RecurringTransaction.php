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
        'is_installment',
        'transaction_id',
        'credit_card_account_id',
        'total_amount',
        'total_months',
        'remaining_months',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_date' => 'date',
        'last_run_date' => 'date',
        'is_active' => 'boolean',
        'is_installment' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function creditCardAccount()
    {
        return $this->belongsTo(Account::class, 'credit_card_account_id');
    }

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
