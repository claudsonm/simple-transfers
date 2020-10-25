<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

final class TransactionRequestedWithoutEnoughFunds extends ShouldBeStored
{
}
