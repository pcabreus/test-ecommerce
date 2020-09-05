<?php


namespace App\Domain\Model;


class Currency
{
    public const CURRENCY = [
        'EUR' => 'EUR',
        'USD' => 'USD',
        'CAD' => 'CAD',
        'CHF' => 'CHF',
        'GBP' => 'GBP',
    ];

    private function __construct(string $currency)
    {
        if (!self::isValid($currency)) {
            throw new \InvalidArgumentException();
        }
        $this->currency = $currency;
    }

    public static function EUR()
    {
        return new static (self::CURRENCY['EUR']);
    }

    public static function USD()
    {
        return new static (self::CURRENCY['USD']);
    }

    private string $currency;

    public function equals(Currency $currency): bool
    {
        return $this->currency === $currency->getCurrency();
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public static function isValid($currency)
    {
        return in_array($currency, self::CURRENCY);
    }

    public function __toString()
    {
        return $this->currency;
    }


}