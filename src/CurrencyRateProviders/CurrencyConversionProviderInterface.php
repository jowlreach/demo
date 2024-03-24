<?php

namespace App\CurrencyRateProviders;

use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\DTO\CurrencyConversionResultInterface;

interface CurrencyConversionProviderInterface
{
    public function convert(CurrencyConversionRequest $currencyConversionRequest): CurrencyConversionResultInterface;
}