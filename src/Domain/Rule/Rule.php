<?php


namespace App\Domain\Rule;


use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;

interface Rule
{
    public function check(Cart $cart, CartItem $cartItem): bool;
}