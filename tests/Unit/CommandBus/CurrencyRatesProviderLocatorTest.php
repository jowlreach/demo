<?php

namespace App\Tests\Unit\CommandBus;

use App\CommandBus\CurrencyRatesProviderLocator;
use App\CurrencyRateProviders\CurrencyConversionProviderInterface;
use App\CurrencyRateProviders\DTO\CurrencyConversionRequest;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class CurrencyRatesProviderLocatorTest extends TestCase
{
    private MockObject|ContainerInterface $container;
    private CurrencyRatesProviderLocator $currencyRatesProvider;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createMock(ContainerInterface::class);

        $this->currencyRatesProvider = new CurrencyRatesProviderLocator($this->container);
    }

    /**
     * @test
     */
    public function itWillReturnProviderCalledByName(): void
    {
        $this->container->expects(self::once())
            ->method('get')
            ->willReturnMap([
                ['exchangerates', $this->createStub(CurrencyConversionProviderInterface::class)],
            ]);

        $this->container->expects(self::once())
            ->method('has')
            ->with('exchangerates')
            ->willReturn(true);

        $request = new CurrencyConversionRequest(
            new Money(1000, new Currency('EUR')),
            new Currency('USD')
        );

        $response = $this->currencyRatesProvider->process('exchangerates', $request);

        $this->assertIsObject($response);
    }

    /**
     * @test
     */
    public function itWillThrowExceptionWhenObjectDoesNotExist(): void
    {
        $this->container->expects(self::once())
            ->method('has')
            ->with('exchangerates')
            ->willReturn(false);

        $request = new CurrencyConversionRequest(
            new Money(1000, new Currency('EUR')),
            new Currency('USD')
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Service with exchangerates name does not exist');
        $this->currencyRatesProvider->process('exchangerates', $request);
    }
}