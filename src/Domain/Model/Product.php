<?php


namespace App\Domain\Model;


class Product
{
    private string $code;
    private int $basePrice = 0;

    public function __construct(string $code, int $basePrice)
    {
        $this->code = $code;
        $this->basePrice = $basePrice;
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
}