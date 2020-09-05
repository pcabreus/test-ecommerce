<?php


namespace App\Domain\Rule;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;

class RuleManager
{
    /** @var array|iterable|Rule[] */
    private iterable $rules = [];

    public function addRule(Rule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function check(Cart $cart, CartItem $cartItem)
    {
        foreach ($this->rules as $rule) {
            if (!$rule->check($cart, $cartItem)) {
                return false;
            }
        }

        return true;
    }
}