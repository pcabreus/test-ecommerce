<?php


namespace App\Domain\Model;


use App\Domain\Model\Currency;

class Product
{
    private string $code;
    private int $basePrice = 0;
    private Currency $currency;

    public function __construct(string $code, int $basePrice, Currency $currency)
    {
        $this->code = $code;
        $this->basePrice = $basePrice;
        $this->currency = $currency;
    }

    public function equals(Product $product)
    {
        return $this->code === $product->getCode();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getBasePrice(): int
    {
        return $this->basePrice;
    }

    public function setBasePrice(int $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getCurrency(): \App\Domain\Model\Currency
    {
        return $this->currency;
    }

    public function setCurrency(\App\Domain\Model\Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}