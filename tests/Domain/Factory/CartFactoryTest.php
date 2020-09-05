<?php

namespace App\Tests\Domain\Factory;

use App\Domain\Factory\CartFactory;
use App\Domain\Model\Cart;
use PHPUnit\Framework\TestCase;

class CartFactoryTest extends TestCase
{

    public function testCreate()
    {
        $factory = new CartFactory();

        $cart = $factory->create();

        $this->assertInstanceOf(Cart::class, $cart);
    }
}
