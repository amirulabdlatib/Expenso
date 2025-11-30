<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'type',
        'name',
        'total_amount',
        'total_paid',
        'balance'
    ];

    public function loanPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }
}
