<?php

namespace App\CurrencyRateProviders\DTO;

use Money\Currency;
use Money\Money;

final class CurrencyConversionRequest
{
    private Money $fromCurrency;
    private Currency $toCurrency;

    public function __construct(
        Money $fromCurrency,
        Currency $toCurrency,
    ) {
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
    }

    public function getFromCurrency(): Money
    {
        return $this->fromCurrency;
    }

    public function getToCurrency(): Currency
    {
        return $this->toCurrency;
    }
}