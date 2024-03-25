<?php

namespace App\CurrencyRateProviders\AnotherTestRatesProvider;

use App\CurrencyRateProviders\CurrencyConversionProviderInterface;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\DTO\CurrencyConversionResult;
use App\CurrencyRateProviders\DTO\CurrencyConversionResultInterface;
use Money\Currency;
use Money\Money;

final class AnotherTestRatesProvider implements CurrencyConversionProviderInterface
{
    public function convert(CurrencyConversionRequest $currencyConversionRequest): CurrencyConversionResultInterface
    {
        return new CurrencyConversionResult(
            new Money(1000, new Currency('EUR')),
            new Money(1200, new Currency('USD')),
            '1.2'
        );
    }
}