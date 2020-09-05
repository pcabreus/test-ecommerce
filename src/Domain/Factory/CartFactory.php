<?php


namespace App\Domain\Factory;


use App\Domain\Model\Cart;

class CartFactory
{
    public function create()
    {
        return new Cart();
    }
}