<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'icon', 'color', 'user_id', 'is_default'];

    /**
     * Scope: return all categories visible to the current user
     * (system defaults + their own custom ones)
     */
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsCustomAttribute(): bool
    {
        return !is_null($this->user_id);
    }
}
