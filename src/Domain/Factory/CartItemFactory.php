<?php


namespace App\Domain\Factory;


use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Domain\Model\Units;

class CartItemFactory
{
    public function create(Product $product, int $units = 0)
    {
        return new CartItem($product, Units::create($units));
    }
}