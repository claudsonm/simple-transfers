<?php

namespace App\Observers;

use App\Aggregates\WalletAggregateRoot;
use App\Models\User;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the user "created" event.
     */
    public function created(User $user)
    {
        $newUuid = Str::uuid()->toString();
        WalletAggregateRoot::retrieve($newUuid)
            ->createWalletFor($user)
            ->persist();
    }
}
