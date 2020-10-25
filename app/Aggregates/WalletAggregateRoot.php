<?php

namespace App\Aggregates;

use App\Models\User;
use App\StorableEvents\WalletCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class WalletAggregateRoot extends AggregateRoot
{
    public function createWalletFor(User $user): self
    {
        $this->recordThat(new WalletCreated($user));

        return $this;
    }
}
