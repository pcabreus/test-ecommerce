<?php

namespace App\Tests\Domain\Cart;

use App\Application\Cart\CartContext;
use App\Domain\Cart\CartCalculator;
use App\Domain\Cart\VoucherProcessor;
use App\Domain\Factory\CartFactory;
use App\Domain\Factory\CartItemFactory;
use App\Domain\Factory\VoucherFactory;
use App\Domain\Model\Cart;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use App\Domain\Rule\ProductsLimit;
use App\Domain\Rule\ProductsLimitException;
use App\Domain\Rule\RuleManager;
use App\Domain\Rule\UnitsLimit;
use App\Domain\Rule\UnitsLimitException;
use App\Infrastructure\Currency\ExchangeRatesApiIOProvider;
use PHPUnit\Framework\TestCase;

class CartContextTest extends TestCase
{
    private CartContext $cartContext;
    private VoucherFactory $voucherFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $cartFactory = new CartFactory();
        $cartItemFactory = new CartItemFactory();
        $ruleManager = (new RuleManager())
            ->addRule(new ProductsLimit())
            ->addRule(new UnitsLimit());

        $exchangeProvider = $this->createMock(ExchangeRatesApiIOProvider::class);

        $exchangeProvider->method('needExchange')->willReturn(false);

        $cartCalculator = new CartCalculator(new VoucherProcessor(), $exchangeProvider);
        $this->cartContext = new CartContext(
            $cartFactory,
            $cartItemFactory,
            $ruleManager,
            $cartCalculator,
            $exchangeProvider
        );
        $this->voucherFactory = new VoucherFactory();
    }

    public function testCreateContext()
    {
        $context = $this->cartContext->clearContext(Currency::EUR());

        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add some product with some amount
        $context->addProductToCart($this->catalog()[1], 10);
        $this->assertEquals(1, $context->countInCart());
        $this->assertEquals(10, $context->countUnitsByProduct($this->catalog()[1]));

        // Add another product
        $context->addProductToCart($this->catalog()[2], 10);
        $this->assertEquals(2, $context->countInCart());

        //Add an already added product
        $context->addProductToCart($this->catalog()[2], 20);
        $this->assertEquals(2, $context->countInCart());
        $this->assertEquals(30, $context->countUnitsByProduct($this->catalog()[2]));

        //Remove some units of an already added product
        $context->removeProductToCart($this->catalog()[2], 15);
        $this->assertEquals(2, $context->countInCart());
        $this->assertEquals(15, $context->countUnitsByProduct($this->catalog()[2]));

        //Remove an already added product
        $context->removeProductToCart($this->catalog()[2]);
        $this->assertEquals(1, $context->countInCart());

        //Remove an already removed product, for now nothing happen no exception thrown
        $context->removeProductToCart($this->catalog()[2]);
        $this->assertEquals(1, $context->countInCart());
    }

    public function testCalculate()
    {
        $context = $this->cartContext->clearContext(Currency::EUR());

        $voucher = $this->voucherFactory->create($this->catalog()[1], 9, 3);

        $context->addVoucher($voucher);

        $context->addProductToCart($this->catalog()[1], 1);
        $this->assertEquals(10, $context->total());

        $context->addProductToCart($this->catalog()[1], 3);
        $this->assertEquals(36, $context->total());
    }

    public function testProductsLimitRuleCreateContext()
    {
        $this->expectException(ProductsLimitException::class);

        $context = $this->cartContext->clearContext(Currency::EUR());
        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add multiple product with some amount
        foreach ($this->catalog() as $product) {
            $context->addProductToCart($product, 10);
        }

        $this->assertEquals(1, $context->countInCart());
    }

    public function testUnitsLimitRuleCreateContext()
    {
        $this->expectException(UnitsLimitException::class);

        $context = $this->cartContext->clearContext(Currency::EUR());
        // Creating the context at init, the cart is created
        $this->assertInstanceOf(Cart::class, $context->getCart());

        // Add some product with some amount
        $context->addProductToCart($this->catalog()[1], 50);
        $this->assertEquals(1, $context->countInCart());

        $context->addProductToCart($this->catalog()[1], 1);
    }

    private function catalog()
    {
        return [
            1 => new Product('t-shirt', 10, Currency::EUR()),
            2 => new Product('phone', 20, Currency::EUR()),
            3 => new Product('tv', 30, Currency::EUR()),
            4 => new Product('laptop', 40, Currency::EUR()),
            5 => new Product('book', 50, Currency::EUR()),
            6 => new Product('pen', 60, Currency::EUR()),
            7 => new Product('pendrive', 70, Currency::EUR()),
            8 => new Product('fan', 80, Currency::EUR()),
            9 => new Product('leaf', 90, Currency::EUR()),
            10 => new Product('notebook', 100, Currency::EUR()),
            11 => new Product('short', 110, Currency::EUR()),
        ];
    }
}
