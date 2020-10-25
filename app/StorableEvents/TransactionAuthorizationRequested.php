<?php

namespace App\StorableEvents;

use Money\Money;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class TransactionAuthorizationRequested extends ShouldBeStored
{
    public Money $amount;

    public int $payeeId;

    public function __construct(Money $amount, int $payeeId)
    {
        $this->amount = $amount;
        $this->payeeId = $payeeId;
    }
}
