<?php

namespace App\Domain\Model;

use Symfony\Component\Uid\Uuid;

class CartItem
{
    private Uuid $code;
    private Units $units;
    private Product $product;

    public function __construct(Product $product, Units $units)
    {
        $this->code =  Uuid::v4();
        $this->units = $units;
        $this->product = $product;
    }

    public function equals(CartItem $cartItem)
    {
        return $this->getProduct()->equals($cartItem->getProduct());
    }

    public function addUnits(CartItem $product)
    {
        $this->units->addUnits($product->getUnits());
    }

    public function getUnits(): Units
    {
        return $this->units;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCode()
    {
        return $this->code;
    }
}