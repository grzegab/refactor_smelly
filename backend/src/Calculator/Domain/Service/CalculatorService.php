<?php

declare(strict_types=1);

namespace App\Calculator\Domain\Service;

use App\Helpers\FileDecode;
use Generator;

class CalculatorService
{
    public const RATE_EU = 0.01;
    public const RATE_NOT_EU = 0.02;

    public function __construct(
        private readonly FileDecode $fileDecode,
        private readonly CountryService $countryService
    )
    {
    }

    public function calculate(Generator|array $fileContent, array $rates): array
    {
        $amountCalculations = [];
        foreach ($fileContent as $parsedInputRow) {
            $input = $this->fileDecode->decodeContent($parsedInputRow, true);

            if (! $this->countryService->isValid($input['bin'])) {
                continue;
            }

            $isEuCountry = $this->countryService->isEuCountry($input['bin']);
            $rate = $this->getRateForCurrency($rates, $input['currency']);

            $amountCalculations[] = $this->calculateAmount(
                $rate,
                $isEuCountry,
                $input['currency'],
                (float)$input['amount']
            );
        }

        return $amountCalculations;
    }


    private function calculateAmount(float $rate, bool $isEuCountry, string $currencyCode, float $amount): float
    {
        $currencyAmount = $this->calculateRateCurrency($rate, $currencyCode, $amount);

        return $this->calculateRateCountry($isEuCountry, $currencyAmount);
    }

    private function getRateForCurrency(array $rates, ?string $currencyCode): float
    {
        if ($currencyCode === null) {
            return 1;
        }

        if (!empty($rates[$currencyCode])) {
            return $rates[$currencyCode];
        }

        return 1;
    }

    private function calculateRateCurrency(float $rate, string $currencyCode, float $amount): float
    {
        if ($currencyCode !== 'EUR' && $rate !== 1.0) {
            $amount /= $rate;
        }

        return $amount;
    }

    private function calculateRateCountry(bool $isEuCountry, float $amount): float
    {
        return $amount * ($isEuCountry ? self::RATE_EU : self::RATE_NOT_EU);
    }
}