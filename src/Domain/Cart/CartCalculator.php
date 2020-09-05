<?php


namespace App\Domain\Cart;


use App\Domain\Currency\ExchangeProvider;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Currency;
use App\Domain\Model\Voucher;

class CartCalculator
{
    private VoucherProcessor $voucherProcessor;
    private ExchangeProvider $exchangeProvider;

    public function __construct(VoucherProcessor $voucherProcessor, ExchangeProvider $exchangeProvider)
    {
        $this->voucherProcessor = $voucherProcessor;
        $this->exchangeProvider = $exchangeProvider;
    }

    /**
     * @param Cart $cart
     * @param Voucher[] $vouchers
     * @param $targetCurrency
     * @return int
     */
    public function calculate(Cart $cart, iterable $vouchers, $targetCurrency)
    {
        $total = 0;
        foreach ($cart->getItems() as $item) {
            $total += $this->calculateItemTotal($item, $vouchers, $targetCurrency);
        }

        return $total;
    }

    /**
     * @param CartItem $item
     * @param Voucher[] $vouchers
     * @param Currency $targetCurrency
     *
     * @return int
     */
    private function calculateItemTotal(CartItem $item, iterable $vouchers, Currency $targetCurrency)
    {
        $basePrice = $item->getProduct()->getBasePrice();
        foreach ($vouchers as $voucher) {
            $basePrice = $this->voucherProcessor->process($item, $voucher, $basePrice);
        }

        if ($this->exchangeProvider->needExchange($item->getProduct(), $targetCurrency)) {
            $basePrice = $this->exchangeProvider->exchange(
                $basePrice,
                $item->getProduct()->getCurrency(),
                $targetCurrency
            );
        }

        return $basePrice * $item->getUnits()->value();
    }
}