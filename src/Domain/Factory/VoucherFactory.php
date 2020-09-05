<?php


namespace App\Domain\Factory;


use App\Domain\Model\Product;
use App\Domain\Model\Voucher;

class VoucherFactory
{
    public function create(Product $product, int $amount, int $min, int $usageLimit = null)
    {
        return (new Voucher())
            ->setProduct($product)
            ->setAmount($amount)
            ->setMin($min)
            ->setUsageLimit($usageLimit);
    }
}