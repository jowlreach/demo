<?php

namespace App\Controller;

use App\CommandBus\CurrencyRatesProviderLocator;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\DTO\CurrencyConversionResult;
use Money\Currency;
use Money\MoneyFormatter;
use Money\MoneyParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CurrencyConverterController extends AbstractController
{
    private CurrencyRatesProviderLocator $currencyRatesProviderLocator;
    private MoneyFormatter $moneyFormatter;
    private MoneyParser $moneyParser;

    public function __construct(
        CurrencyRatesProviderLocator $currencyRatesProviderLocator,
        #[Autowire(service: 'Money\Formatter\DecimalMoneyFormatter')] MoneyFormatter $moneyFormatter,
        #[Autowire(service: 'Money\Parser\DecimalMoneyParser')] MoneyParser $moneyParser
    ) {
        $this->currencyRatesProviderLocator = $currencyRatesProviderLocator;
        $this->moneyFormatter = $moneyFormatter;
        $this->moneyParser = $moneyParser;
    }

    //should be added swagger docs
    #[Route('/currency/converter/{provider}', name: 'app_currency_converter')]
    public function index(string $provider, Request $request): Response
    {
        try {
            //here validation of params should be, I omitted it for simplicity
            $currencyFrom = $request->get('currencyFrom');
            $currencyTo = $request->get('currencyTo');
            $amount = $request->get('amount');

            $request = new CurrencyConversionRequest(
                $this->moneyParser->parse($amount, new Currency($currencyFrom)),
                new Currency($currencyTo)
            );

            /** @var CurrencyConversionResult $response */
            $response = $this->currencyRatesProviderLocator->process(
                $provider,
                $request
            );

            return new JsonResponse(
                [
                    'data' => [
                        'currencyFrom' => $response->getCurrencyFrom()->getCurrency()->getCode(),
                        'currencyTo' => $response->getCurrencyTo()->getCurrency()->getCode(),
                        'initialAmount' => $this->moneyFormatter->format($response->getCurrencyFrom()),
                        'convertedAmount' => $this->moneyFormatter->format($response->getCurrencyTo()),
                        'rate' => $response->getRate(),
                    ],
                    'status' => 'success',
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            //here also logging should be I omited it on purpose for simplicity
            return new JsonResponse(['data' => ['errors' => $e->getMessage()], 'status' => 'fail'], Response::HTTP_BAD_REQUEST);
        }
    }
}
