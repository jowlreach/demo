<?php

namespace App\CurrencyRateProviderClients\ExchangeRates\Message;

class ExchangeRateResponseMessage implements \JsonSerializable
{
    private string $result;
    private array $rates;

    public function __construct(
        string $result,
        string $baseCurrency,
        array $rates
    ) {
        $this->result = $result;
        $this->rates = $rates;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getRates(): array
    {
        return $this->rates;
    }

    public static function createFromResponse(array $responseData): self
    {
        if (isset($responseData['result']) === false) {
            throw new \OutOfRangeException('Invalid response from ExchangeRates');
        }

        return new self($responseData['result'], $responseData['base_code'], $responseData['rates'] ?? []);
    }

    public function jsonSerialize(): array
    {
        return [
            'rates' => $this->rates,
        ];
    }
}