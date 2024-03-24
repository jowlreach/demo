<?php

namespace App\CommandBus;

use App\CurrencyRateProviders\CurrencyConversionProviderInterface;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use App\CurrencyRateProviders\DTO\CurrencyConversionResultInterface;
use Psr\Container\ContainerInterface;

final class CurrencyRatesProviderLocator
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(string $serviceName, CurrencyConversionRequest $request): CurrencyConversionResultInterface
    {
        //should be added try cath, omitted it for simplicity
        if ($this->container->has($serviceName) === false) {
            throw new \InvalidArgumentException(sprintf('Service with %s name does not exist', $serviceName));
        }

        /** @var CurrencyConversionProviderInterface $service */
        $service = $this->container->get($serviceName);

        return $service->convert($request);
    }
}