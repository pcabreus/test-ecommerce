<?php

namespace App\Tests\Domain\Factory;

use App\Domain\Factory\CartItemFactory;
use App\Domain\Model\CartItem;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use PHPUnit\Framework\TestCase;

class CartItemFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new CartItemFactory();

        $product = $factory->create(new Product('A', 10, Currency::EUR()));

        $this->assertInstanceOf(CartItem::class, $product);
        $this->assertEquals(0, $product->getUnits()->value());
    }
}
