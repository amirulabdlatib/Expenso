<?php

namespace App\Models;

use App\Enums\LoanType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'total_amount',
        'initial_paid_amount',
        'total_balance',
        'description',
        'start_date',
    ];

    protected $casts = [
        'type' => LoanType::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function scopeForCurrentUser(Builder $query)
    {
        return $query->where('user_id', Auth::id());
    }
}
