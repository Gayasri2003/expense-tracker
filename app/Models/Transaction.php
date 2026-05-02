<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'account_id',
        'amount',
        'notes',
        'date',
        'type', // income, expense, credit_purchase, principal_repayment, interest_payment
    ];

    public function recurringTransaction()
    {
        return $this->hasOne(RecurringTransaction::class);
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
