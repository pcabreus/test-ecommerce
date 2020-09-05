<?php

namespace App\Infrastructure\Currency;

use App\Domain\Currency\ExchangeProvider;
use App\Domain\Currency\InvalidCurrencyException;
use App\Domain\Model\Currency;
use App\Domain\Model\Product;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Exchange rates API is a free service for current and historical foreign exchange rates
 * published by the European Central Bank
 */
class ExchangeRatesApiIOProvider implements ExchangeProvider
{
    private const API = 'https://api.exchangeratesapi.io/latest?base=';
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param int $amount
     * @param Currency $base
     * @param Currency $target
     * @return float
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function exchange(int $amount, Currency $base, Currency $target): float
    {
        $exchange = $this->httpClient
            ->request('GET', sprintf(self::API . '%s', $base->getCurrency()))
            ->toArray()['rates'][$target->getCurrency()];
        try {
            return $exchange * $amount;
        } catch (\Throwable $exception) {
            throw new InvalidCurrencyException(sprintf('The currency `%s` is invalid', $target));
        }
    }

    public function needExchange(Product $product, Currency $targetCurrency): bool
    {
        return !$product->getCurrency()->equals($targetCurrency);
    }

}