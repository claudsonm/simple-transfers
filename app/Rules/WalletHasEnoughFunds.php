<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;

class WalletHasEnoughFunds implements Rule
{
    protected Wallet $wallet;

    /**
     * Create a new rule instance.
     */
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->wallet->balanceIsGreaterThanOrEqualTo($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is higher than the balance available.';
    }
}
