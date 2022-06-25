<?php

declare(strict_types=1);

namespace App\Calculator\App;

use App\Calculator\Domain\Service\InputFileService;
use App\Calculator\Domain\Service\CalculatorService;
use App\Calculator\Domain\Service\RatesService;

class Service
{
    public function __construct(
        private readonly InputFileService $inputFileService,
        private readonly RatesService $ratesService,
        private readonly CalculatorService $calculatorService
    )
    {
    }

    public function calculateRates(string $inputFile): array
    {
        $parsedInputFileContent = $this->inputFileService->getContent($inputFile);
        $rates = $this->ratesService->getRates();

        return $this->calculatorService->calculate($parsedInputFileContent, $rates);
    }
}
