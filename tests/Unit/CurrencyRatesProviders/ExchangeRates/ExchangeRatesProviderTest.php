<?php

namespace App\Tests\Unit\CurrencyRatesProviders\ExchangeRates;

use App\CurrencyRateProviderClients\ExchangeRates\ExchangeRatesClient;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateRequestMessage;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateResponseMessage;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\ExchangeRates\ExchangeRatesProvider;
use App\Service\CurrencyConverter\CurrencyConverter;
use GuzzleHttp\Psr7\Uri;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExchangeRatesProviderTest extends TestCase
{
    private ExchangeRatesProvider $exchangeRatesProvider;
    private ExchangeRatesClient|MockObject $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(ExchangeRatesClient::class);
        $this->exchangeRatesProvider = new ExchangeRatesProvider(
            $this->client,
            new CurrencyConverter()
        );
    }

    /**
     * @@test
     */
    public function itWIllReturnCurrencyConversionResultSuccessfully(): void
    {
        $requestMessage = new ExchangeRateRequestMessage('EUR');
        $responseMessage = new ExchangeRateResponseMessage('success', 'EUR', ['USD' => 1.25]);
        $this->client
            ->expects($this->once())
            ->method('getRates')
            ->with(new Uri('https://open.er-api.com'), $requestMessage)
            ->willReturn($responseMessage);

        $request = new CurrencyConversionRequest(
            new Money(1000, new Currency('EUR')),
            new Currency('USD')
        );

        $response = $this->exchangeRatesProvider->convert($request);

        $this->assertSame('1250', $response->getCurrencyTo()->getAmount());
        $this->assertSame('USD', $response->getCurrencyTo()->getCurrency()->getCode());
        $this->assertSame('1.25', $response->getRate());
    }

    /**
     * @test
     */
    public function itWIllThrowExceptionWhenCurrencyPairIsNotFound(): void
    {
        $requestMessage = new ExchangeRateRequestMessage('EUR');
        $responseMessage = new ExchangeRateResponseMessage('success', 'EUR', []);
        $this->client
            ->expects($this->once())
            ->method('getRates')
            ->with(new Uri('https://open.er-api.com'), $requestMessage)
            ->willReturn($responseMessage);

        $request = new CurrencyConversionRequest(
            new Money(1000, new Currency('EUR')),
            new Currency('USD')
        );

        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('Currency rate is not found');
        $this->exchangeRatesProvider->convert($request);
    }
}