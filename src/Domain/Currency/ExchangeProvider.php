<?php

namespace App\Domain\Currency;

use App\Domain\Model\Currency;
use App\Domain\Model\Product;

interface ExchangeProvider
{
    public function exchange(int $amount, Currency $base, Currency $target): float;

    public function needExchange(Product $product, Currency $targetCurrency): bool;
}