<?php

namespace App\Tests\Unit\CurrencyRateProviderClients\ExchangeRates;

use App\CurrencyRateProviderClients\ExchangeRates\ExchangeRatesClient;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateRequestMessage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExchangeRatesClientTest extends TestCase
{
    private ClientInterface|MockObject $httpClient;
    private ExchangeRatesClient $exchangeRatesClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->exchangeRatesClient = new ExchangeRatesClient($this->httpClient);
    }

    /**
     * @test
     */
    public function itWillReturnSuccessfullyRates(): void
    {
        $uri = new Uri('https://open.er-api.com');

        $requestMessage = new ExchangeRateRequestMessage('EUR');

        // here should be called to a json file and load it via code, I added inline for simplicity
        $ratesJsonMessage = '{"result":"success","rates":{"USD":1},"base_code":"EUR"}';
        $guzzleResponse = new Response(200, [], $ratesJsonMessage);
        $this->httpClient->method('request')->willReturn($guzzleResponse);

        $response = $this->exchangeRatesClient->getRates($uri, $requestMessage);

        $this->assertSame(
            [
                'rates' => [
                    'USD' => 1,
                ],
            ],
            $response->jsonSerialize()
        );
    }

    /**
     * @test
     */
    public function itWillThrowExceptionOnInvalidResponse(): void
    {
        $uri = new Uri('https://open.er-api.com');

        $requestMessage = new ExchangeRateRequestMessage('EUR');

        // here should be called to a json file and load it via code, I added inline for simplicity
        $ratesJsonMessage = '{"rates":{"USD":1},"base_code":"EUR"}';
        $guzzleResponse = new Response(200, [], $ratesJsonMessage);
        $this->httpClient->method('request')->willReturn($guzzleResponse);

        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('Invalid response from ExchangeRates');
        $this->exchangeRatesClient->getRates($uri, $requestMessage);
    }
}