<?php


namespace App\Domain;


class PriceFormatter
{
    public static function transform($number)
    {
        return (int)$number * 100;
    }

    public static function reverse(int $number)
    {
        return round($number / 100, 2);
    }
}