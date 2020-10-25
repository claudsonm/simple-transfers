<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * @param int|float $value
     */
    public function balanceIsGreaterThanOrEqualTo($value): bool
    {
        if (is_float($value)) {
            $value = $value * 100;
        }

        return $this->balance >= $value;
    }
}
