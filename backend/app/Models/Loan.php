<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'type',
        'name',
        'total_amount',
        'total_paid',
        'balance'
    ];
}
