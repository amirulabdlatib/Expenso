<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $fillable = [
        'loan_id',
        'transaction_id',
        'amount',
        'direction',
        'date',
    ];
}
