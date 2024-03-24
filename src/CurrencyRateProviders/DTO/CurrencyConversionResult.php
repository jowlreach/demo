<?php

namespace App\CurrencyRateProviders\DTO;

use Money\Money;

final class CurrencyConversionResult implements CurrencyConversionResultInterface
{
    private Money $currencyFrom;
    private Money $currencyTo;
    private string $rate;

    public function __construct(
        Money $currencyFrom,
        Money $currencyTo,
        string $rate
    ) {
        $this->currencyFrom = $currencyFrom;
        $this->currencyTo = $currencyTo;
        $this->rate = $rate;
    }

    public function getCurrencyFrom(): Money
    {
        return $this->currencyFrom;
    }

    public function getCurrencyTo(): Money
    {
        return $this->currencyTo;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function jsonSerialize(): array
    {
        return [
            'currencyFrom' => $this->currencyFrom->getCurrency()->getCode(),
            'currencyTo' => $this->currencyTo->getCurrency()->getCode(),
            'rate' => $this->rate,
        ];
    }
}