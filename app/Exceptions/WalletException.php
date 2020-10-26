<?php

namespace App\Exceptions;

use DomainException;

class WalletException extends DomainException
{
    public static function notEnoughFunds(): self
    {
        return new static("The wallet doesn't have enough funds.", 403);
    }
}
