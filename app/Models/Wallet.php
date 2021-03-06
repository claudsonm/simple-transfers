<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Money;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'account',
        'branch',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'integer',
    ];

    /**
     * The wallet owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet's balance as a money instance.
     */
    public function getMoneyBalanceAttribute(): Money
    {
        return Money::BRL($this->balance);
    }

    public function balanceIsGreaterThanOrEqualTo(Money $money): bool
    {
        return $this->moneyBalance->greaterThanOrEqual($money);
    }
}
