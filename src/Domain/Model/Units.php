<?php


namespace App\Domain\Model;


class Units
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("The units should be a value higher than 0.");
        }

        $this->value = $value;
    }

    public static function create(int $value = 0)
    {
        return (new self($value));
    }

    public static function sum(Units $unitsA, Units $unitsB)
    {
        return new Units($unitsA->value() + $unitsB->value());
    }

    public static function subs(Units $unitsA, Units $unitsB)
    {
        return new Units($unitsA->value() - $unitsB->value());
    }

    public function value(): int
    {
        return $this->value;
    }

    public function addUnits(Units $units)
    {
        $this->value += $units->value();
    }

    public function removeUnits(Units $units)
    {
        $this->value -= $units->value();
    }

}