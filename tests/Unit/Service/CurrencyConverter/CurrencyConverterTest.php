<?php

namespace App\Tests\Unit\Service\CurrencyConverter;

use App\Service\CurrencyConverter\CurrencyConverter;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase
{
    /**
     * @test
     */
    public function itWillConvertMoney(): void
    {
        $currencyConverter = new CurrencyConverter();

        $initialMoney = new Money(1000, new Currency('EUR'));
        $currencyToConvert = new Currency('USD');
        $response = $currencyConverter->convertTo($initialMoney, $currencyToConvert, '1.25');

        $this->assertSame('1250', $response->getAmount());
        $this->assertSame('USD', $response->getCurrency()->getCode());
    }
}