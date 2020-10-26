<?php

namespace Tests\Aggregate;

use App\Aggregates\WalletAggregateRoot;
use App\Exceptions\WalletException;
use App\Models\User;
use App\StorableEvents\TransactionAuthorizationRequested;
use App\StorableEvents\TransactionRequestedWithoutEnoughFunds;
use App\StorableEvents\WalletCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Money\Money;
use Tests\TestCase;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class WalletAggregateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_assures_the_wallet_has_enough_funds_to_perform_the_transaction()
    {
        $tom = null;
        User::withoutEvents(function () use (&$tom) {
            $tom = User::factory()->create();
        });

        WalletAggregateRoot::fake()
            ->given([new WalletCreated($tom)])
            ->when(function (WalletAggregateRoot $walletAggregate) {
                try {
                    $walletAggregate->createTransaction(Money::BRL(1575), 2);
                } catch (WalletException $e) {
                }
            })
            ->assertRecorded(new TransactionRequestedWithoutEnoughFunds())
            ->assertNotRecorded(TransactionAuthorizationRequested::class);
    }
}
