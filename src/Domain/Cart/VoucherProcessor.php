<?php


namespace App\Domain\Cart;


use App\Domain\Model\CartItem;
use App\Domain\Model\Voucher;

class VoucherProcessor
{
    public function isApplicable(Voucher $voucher, CartItem $cartItem)
    {
        return
            $voucher->getProduct()->equals($cartItem->getProduct()) && // If it is the same product type
            (!$voucher->getUsageLimit() || $voucher->getUsageLimit() > 0) && // If it has not limit or it is higher than 0
            $voucher->getMin() < $cartItem->getUnits()->value();
    }

    public function process(CartItem $cartItem, Voucher $voucher, int $basePrice)
    {
        return $this->isApplicable($voucher, $cartItem) ? $voucher->getAmount() : $basePrice;
    }
}