<?php

namespace App\Concerns;

use Money\Money;

trait InteractsWithMoney
{
    public function floatToMoney(float $value): Money
    {
        return Money::BRL($value * 100);
    }
}
