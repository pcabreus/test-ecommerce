<?php

namespace App\Domain\Cart;

use App\Domain\Factory\CartFactory;
use App\Domain\Factory\CartItemFactory;
use App\Domain\Model\Cart;
use App\Domain\Model\Product;
use App\Domain\Model\Units;
use App\Domain\Rule\RuleManager;

class CartContext
{
    private Cart $cart;
    private CartFactory $cartFactory;
    private CartItemFactory $cartItemFactory;
    private RuleManager $ruleManager;

    public function __construct(CartFactory $cartFactory, CartItemFactory $cartItemFactory, RuleManager $ruleManager)
    {
        $this->cartFactory = $cartFactory;
        $this->cartItemFactory = $cartItemFactory;
        $this->ruleManager = $ruleManager;
        $this->clearContext();
    }

    public function clearContext(): self
    {
        $this->cart = $this->cartFactory->create();

        return $this;
    }

    public function getCart(): Cart
    {
        return $this->cart;
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