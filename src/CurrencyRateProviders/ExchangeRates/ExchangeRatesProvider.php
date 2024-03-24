<?php

namespace App\CurrencyRateProviders\ExchangeRates;

use App\CurrencyRateProviderClients\ExchangeRates\ExchangeRatesClient;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateRequestMessage;
use App\CurrencyRateProviderClients\ExchangeRates\Message\ExchangeRateResponseMessage;
use App\CurrencyRateProviders\CurrencyConversionProviderInterface;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\DTO\CurrencyConversionResult;
use App\CurrencyRateProviders\DTO\CurrencyConversionResultInterface;
use App\Service\CurrencyConverter\CurrencyConverter;
use GuzzleHttp\Psr7\Uri;

final class ExchangeRatesProvider implements CurrencyConversionProviderInterface
{
    private ExchangeRatesClient $exchangeRatesClient;
    private CurrencyConverter $converter;

    public function __construct(
        ExchangeRatesClient $exchangeRatesClient,
        CurrencyConverter $converter
    ) {
        $this->exchangeRatesClient = $exchangeRatesClient;
        $this->converter = $converter;
    }

    public function convert(CurrencyConversionRequest $currencyConversionRequest): CurrencyConversionResultInterface
    {
        //hardcoded for simplicity
        $uri = new Uri('https://open.er-api.com');
        $requestMessage = new ExchangeRateRequestMessage($currencyConversionRequest->getFromCurrency()->getCurrency()->getCode());

        //here should be added try catch block, omitted it on purpose for simplicity
        $rates = $this->exchangeRatesClient->getRates($uri, $requestMessage);

        $currencyPairRate = $this->getCurrencyPairRate($rates, $currencyConversionRequest);

        $convertedAmount = $this->converter->convertTo(
            $currencyConversionRequest->getFromCurrency(),
            $currencyConversionRequest->getToCurrency(),
            $currencyPairRate
        );

        return new CurrencyConversionResult(
            $currencyConversionRequest->getFromCurrency(),
            $convertedAmount,
            $currencyPairRate
        );
    }

    /**
     * @throws \OutOfRangeException
     */
    private function getCurrencyPairRate(
        ExchangeRateResponseMessage $rateResponseMessage,
        CurrencyConversionRequest $currencyConversionRequest
    ): string
    {
        $rates = $rateResponseMessage->getRates();

        if (isset($rates[$currencyConversionRequest->getToCurrency()->getCode()]) === false) {
            throw new \OutOfRangeException('Currency rate is not found');
        }

        return (string)$rates[$currencyConversionRequest->getToCurrency()->getCode()];
    }
}