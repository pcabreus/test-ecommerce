<?php

namespace App\Application\Cart;

use App\Domain\Cart\CartCalculator;
use App\Domain\Currency\ExchangeProvider;
use App\Domain\Factory\CartFactory;
use App\Domain\Factory\CartItemFactory;
use App\Domain\Model\Cart;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use App\Domain\Model\Voucher;
use App\Domain\Rule\RuleManager;
use Symfony\Component\HttpClient\Response\CurlResponse;

class CartContext
{
    private Cart $cart;
    private CartFactory $cartFactory;
    private CartItemFactory $cartItemFactory;
    private RuleManager $ruleManager;
    private iterable $vouchers;
    private CartCalculator $cartCalculator;
    private ExchangeProvider $exchangeProvider;
    private ?Currency $baseCurrency = null;

    public function __construct(
        CartFactory $cartFactory,
        CartItemFactory $cartItemFactory,
        RuleManager $ruleManager,
        CartCalculator $cartCalculator,
        ExchangeProvider $exchangeProvider
    ) {
        $this->cartFactory = $cartFactory;
        $this->cartItemFactory = $cartItemFactory;
        $this->ruleManager = $ruleManager;
        $this->cartCalculator = $cartCalculator;
        $this->exchangeProvider = $exchangeProvider;
    }

    public function clearContext(Currency $currency): self
    {
        $this->cart = $this->cartFactory->create();
        $this->baseCurrency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->baseCurrency;
    }

    public function changeCurrency(Currency $currency)
    {
        return $this->baseCurrency = $currency;
    }


    public function total()
    {
        return $this->cartCalculator->calculate($this->cart, $this->vouchers, $this->baseCurrency);
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function addVoucher(Voucher $voucher)
    {
        $this->vouchers[] = $voucher;

        return $this;
    }

    public function addProductToCart(Product $product, $units)
    {
        $cartItem = $this->cartItemFactory->create($product, $units);

        // This throw exceptions if fails
        $this->ruleManager->check($this->cart, $cartItem);

        $this->cart->addItem($cartItem);

        return $this;
    }

    public function removeProductToCart(Product $product, $units = null)
    {
        if (null === $cartItem = $this->cart->findItemByProduct($product)) {
            return $this;
        }

        $this->cart->removeItem($cartItem, $units);

        return $this;
    }

    public function countInCart(): int
    {
        return $this->cart->count();
    }

    public function countUnitsByProduct(Product $product): int
    {
        return $this->cart->findItemByProduct($product)->getUnits()->value();
    }
}