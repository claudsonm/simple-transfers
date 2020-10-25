<?php

namespace App\Projectors;

use App\Models\Wallet;
use App\StorableEvents\WalletCreated;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class WalletProjector extends Projector
{
    public function onWalletCreated(WalletCreated $event, string $aggregateUuid)
    {
        Wallet::create([
            'uuid' => $aggregateUuid,
            'branch' => '0001',
            'account' => str_pad(random_int(1000, 99999999), 8, '0', STR_PAD_LEFT),
            'user_id' => $event->user->id,
        ]);
    }
}
