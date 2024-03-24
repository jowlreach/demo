<?php

namespace App\CurrencyRateProviderClients\ExchangeRates\Message;

final class ExchangeRateRequestMessage
{
    private string $currency;

    public function __construct(
      string $currency
    ) {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}