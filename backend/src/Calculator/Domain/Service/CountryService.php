<?php

declare(strict_types=1);

namespace App\Calculator\Domain\Service;

use App\Calculator\Enum\EuCountriesEnum;

class CountryService
{
    private const LOOKUP_URL = 'https://lookup.binlist.net';

    public function __construct(
        private readonly BinListService $binListService,
    )
    {
    }

    public function isValid(?string $bin): bool
    {
        if (is_numeric($bin) && (int)$bin <= 0) {
            return false;
        }

        return !empty($bin);
    }

    public function isEuCountry(string $binValue): bool
    {
        //cache mechanism could be added here, like redis to prevent asking for same data (bin assigned to isEu)
        $countryCode = $this->binListService->lookUpBinListCountry($binValue);

        return $this->isInEuList($countryCode);
    }

    private function isInEuList(string $countryCode): bool
    {
        if (is_numeric($countryCode) || empty($countryCode)) {
            return false;
        }

        $countryCodeToUpper = strtoupper($countryCode);

        return EuCountriesEnum::tryFrom($countryCodeToUpper) !== null;
    }
}