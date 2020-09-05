<?php

namespace App\Tests\Domain\Cart;

use App\Domain\Cart\CartContext;
use App\Domain\Factory\CartFactory;
use App\Domain\Factory\CartItemFactory;
use App\Domain\Model\Cart;
use App\Domain\Model\Product;
use App\Domain\Rule\ProductsLimit;
use App\Domain\Rule\ProductsLimitException;
use App\Domain\Rule\RuleManager;
use App\Domain\Rule\UnitsLimit;
use App\Domain\Rule\UnitsLimitException;
use PHPUnit\Framework\TestCase;

class CartContextTest extends TestCase
{
    private CartContext $cartContext;

    protected function setUp(): void
    {
        parent::setUp();
        $cartFactory = $this->createMock(CartFactory::class);
        $cartItemFactory = new CartItemFactory();

        $ruleManager = (new RuleManager())
            ->addRule(new ProductsLimit())
            ->addRule(new UnitsLimit());

        // Mock examples
        $cartFactory
            ->method('create')
            ->willReturn(new Cart());
        $this->cartContext = new CartContext($cartFactory, $cartItemFactory, $ruleManager);
    }

    public function testCreateContext()
    {
        $context = $this->cartContext->clearContext();

        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add some product with some amount
        $context->addProductToCart($this->catelog()[1], 10);
        $this->assertEquals(1, $context->countInCart());
        $this->assertEquals(10, $context->countUnitsByProduct($this->catelog()[1]));

        // Add another product
        $context->addProductToCart($this->catelog()[2], 10);
        $this->assertEquals(2, $context->countInCart());

        //Add an already added product
        $context->addProductToCart($this->catelog()[2], 20);
        $this->assertEquals(2, $context->countInCart());
        $this->assertEquals(30, $context->countUnitsByProduct($this->catelog()[2]));

        //Remove some units of an already added product
        $context->removeProductToCart($this->catelog()[2], 15);
        $this->assertEquals(2, $context->countInCart());
        $this->assertEquals(15, $context->countUnitsByProduct($this->catelog()[2]));

        //Remove an already added product
        $context->removeProductToCart($this->catelog()[2]);
        $this->assertEquals(1, $context->countInCart());

        //Remove an already removed product, for now nothing happen no exception thrown
        $context->removeProductToCart($this->catelog()[2]);
        $this->assertEquals(1, $context->countInCart());



    }

    public function testProductsLimitRuleCreateContext()
    {
        $this->expectException(ProductsLimitException::class);

        $context = $this->cartContext->clearContext();
        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add multiple product with some amount
        foreach ($this->catelog() as $product) {
            $context->addProductToCart($product, 10);
        }

        $this->assertEquals(1, $context->countInCart());
    }

    public function testUnitsLimitRuleCreateContext()
    {
        $this->expectException(UnitsLimitException::class);

        $context = $this->cartContext->clearContext();
        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add some product with some amount
        $context->addProductToCart($this->catelog()[1], 50);
        $this->assertEquals(1, $context->countInCart());

        $context->addProductToCart($this->catelog()[1], 1);
    }

    private function catelog()
    {
        return [
            1 => new Product('t-shirt', 10),
            2 => new Product('phone', 20),
            3 => new Product('tv', 30),
            4 => new Product('laptop', 40),
            5 => new Product('book', 50),
            6 => new Product('pen', 60),
            7 => new Product('pendrive', 70),
            8 => new Product('fan', 80),
            9 => new Product('leaf', 90),
            10 => new Product('notebook', 100),
            11 => new Product('short', 110),
        ];
    }
}
