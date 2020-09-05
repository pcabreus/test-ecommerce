<?php


namespace App\Domain\Rule;


use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;

class ProductsLimit implements Rule
{
    private const LIMIT = 10;

    public function check(Cart $cart, CartItem $cartItem): bool
    {
        if ((null === $cart->findItem($cartItem)) && self::LIMIT <= $cart->count()) {
            throw new ProductsLimitException("The limit for different products are " . self::LIMIT);
        }

        return true;
    }

}