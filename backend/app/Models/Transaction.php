<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    // TODO:: Add receipt file
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'related_account_id',
        'transfer_pair_id',
        'name',
        'description',
        'debit',
        'credit',
        'transaction_date',
        'receipt',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'related_account_id');
    }

    public function relatedTransaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'transfer_pair_id', 'transfer_pair_id')
            ->whereNotNull('transfer_pair_id')
            ->where('id', '!=', $this->id);
    }

    /**
     * Scope a query to only include records belonging to the currently authenticated user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCurrentUser(Builder $query)
    {
        return $query->where('user_id', Auth::id());
    }

    public static function totalIncome()
    {
        return static::where('user_id', Auth::id())
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Income);
            })
            ->sum('credit');
    }

    public static function totalExpenses()
    {
        return static::where('user_id', Auth::id())
            ->whereHas('category', function ($q) {
                $q->where('type', CategoryType::Expense);
            })
            ->sum('debit');
    }
}
