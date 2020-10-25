<?php

namespace App\Aggregates;

use App\Exceptions\WalletException;
use App\Models\User;
use App\StorableEvents\TransactionAuthorizationRequested;
use App\StorableEvents\TransactionRequestedWithoutEnoughFunds;
use App\StorableEvents\WalletCreated;
use Money\Money;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class WalletAggregateRoot extends AggregateRoot
{
    private Money $balance;

    public function __construct()
    {
        $this->balance = Money::BRL(0);
    }

    public function createWalletFor(User $user): self
    {
        $this->recordThat(new WalletCreated($user));

        return $this;
    }

    /**
     * @throws WalletException
     */
    public function createTransaction(Money $amount, int $payeeId): self
    {
        if (! $this->hasSufficientFundsToSubtract($amount)) {
            $this->recordThat(new TransactionRequestedWithoutEnoughFunds());
            $this->persist();

            throw WalletException::notEnoughFunds();
        }

        $this->recordThat(new TransactionAuthorizationRequested($amount, $payeeId));

        return $this;
    }

    private function hasSufficientFundsToSubtract(Money $amount): bool
    {
        return $this->balance->greaterThanOrEqual($amount);
    }
}
