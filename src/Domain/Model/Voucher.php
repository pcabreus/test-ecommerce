<?php

namespace App\Domain\Model;

use App\Domain\Model\Product;

class Voucher
{
    private Product $product;
    private int $amount = 0;
    private ?int $usageLimit = null;
    private ?int $min = null;

    public function apply()
    {
        if (null !== $this->usageLimit) {
            $this->usageLimit--;
        }

        return $this->amount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(?int $usageLimit): self
    {
        $this->usageLimit = $usageLimit;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(?int $min): self
    {
        $this->min = $min;

        return $this;
    }
}