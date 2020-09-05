<?php


namespace App\Domain\Model;


class Cart
{
    /** @var iterable|CartItem[] */
    private iterable $items = [];

    public function addItem(CartItem $cartItem)
    {
        if (null === $foundProduct = $this->findItem($cartItem)) {
            $this->items[$cartItem->getCode()->toRfc4122()] = $cartItem;

            return $this;
        }

        $foundProduct->addUnits($cartItem);

        return $this;
    }

    public function removeItem(CartItem $cartItem, $units = null)
    {
        if ($units) {
            $subUnits = Units::subs($cartItem->getUnits(), Units::create($units));

            if ($subUnits->value() > 0) {
                $cartItem->getUnits()->removeUnits($subUnits);

                return $this;
            }
        }

        unset($this->items[$cartItem->getCode()->toRfc4122()]);

        return $this;
    }

    public function findItem(CartItem $targetCartItem)
    {
        return $this->findItemByProduct($targetCartItem->getProduct());
    }

    public function findItemByProduct(Product $targetProduct)
    {
        foreach ($this->items as $cartItem) {
            if ($cartItem->getProduct()->equals($targetProduct)) {
                return $cartItem;
            }
        }

        return null;
    }

    public function count()
    {
        return count($this->items);
    }
}