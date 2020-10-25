<?php

namespace Tests\Unit;

use Money\Money;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    /** @test */
    public function it_checks_if_the_wallet_has_enough_balance()
    {
        $wallet = new Wallet();
        $wallet->balance = 1000;

        $this->assertTrue($wallet->balanceIsGreaterThanOrEqualTo(Money::BRL(1000)));
        $this->assertFalse($wallet->balanceIsGreaterThanOrEqualTo(Money::BRL(1001)));
        $this->assertTrue($wallet->balanceIsGreaterThanOrEqualTo(Money::BRL(-500)));
    }
}
