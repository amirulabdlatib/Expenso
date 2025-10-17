<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'icon',
        'balance',
        'currency',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function totalBalance()
    {
        return $this->accounts()
                    ->where('is_active',false)
                    ->sum('balance')
    }
}
