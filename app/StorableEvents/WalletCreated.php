<?php

namespace App\StorableEvents;

use App\Models\User;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class WalletCreated extends ShouldBeStored
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
