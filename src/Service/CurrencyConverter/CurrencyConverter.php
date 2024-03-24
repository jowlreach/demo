<?php

namespace App\Service\CurrencyConverter;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Money;

class CurrencyConverter
{
    public function convertTo(Money $fromCurrency, Currency $toCurrency, string $rate): Money
    {
        $exchange = new FixedExchange([$fromCurrency->getCurrency()->getCode() => [$toCurrency->getCode() => $rate]]);

        $converter = new Converter(new ISOCurrencies(), $exchange);

        return $converter->convert($fromCurrency, $toCurrency);
    }
}