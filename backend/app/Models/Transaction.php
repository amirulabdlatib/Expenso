<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    // TODO:: Add receipt file
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'related_account_id',
        'name',
        'description',
        'debit',
        'credit',
        'transaction_date',
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
