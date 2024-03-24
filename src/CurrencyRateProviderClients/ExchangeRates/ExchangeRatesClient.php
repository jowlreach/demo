<?php

namespace App\CurrencyRateProviderClients\ExchangeRates;

use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateRequestMessage;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateResponseMessage;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\UriInterface;

class ExchangeRatesClient
{
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getRates(UriInterface $uri, ExchangeRateRequestMessage $exchangeRateRequestMessage): ExchangeRateResponseMessage
    {
        //for simplicity I hardcoded it here, it should be mapped conflig and passed from service
        $path = sprintf('/v6/latest/%s', $exchangeRateRequestMessage->getCurrency());
        $request = $this->httpClient->request(
           'GET',
           $uri->withPath($path)
       );

        $response = $request->getBody()->getContents();
        $responseArray = json_decode($response, true, 512, JSON_THROW_ON_ERROR);

        return ExchangeRateResponseMessage::createFromResponse($responseArray);
    }
}