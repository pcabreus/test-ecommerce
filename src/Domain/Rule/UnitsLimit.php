<?php

namespace App\Domain\Rule;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Units;

class UnitsLimit implements Rule
{
    private const LIMIT = 50;

    public function check(Cart $cart, CartItem $cartItem): bool
    {
        if ((null !== $item = $cart->findItem($cartItem)) && self::LIMIT < $sum = Units::sum(
                $item->getUnits(),
                $cartItem->getUnits()
            )->value()) {
            throw new UnitsLimitException("The limit per units of the same products are " . self::LIMIT . ". current " . $sum);
        }

        return true;
    }

}