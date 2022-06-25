<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Service;

use App\Calculator\Domain\Service\CalculatorService;
use App\Calculator\Domain\Service\CountryService;
use App\Helpers\FileDecode;
use PHPUnit\Framework\TestCase;

class CalculatorServiceTest extends TestCase
{
    /**
     * @dataProvider calculateDataProvider
     */
    public function testCalculate(
        string $amount,
        bool $isEuCountry,
        float $expected
    ): void
    {
        $fileDecode = $this
            ->getMockBuilder(FileDecode::class)
            ->disableOriginalConstructor()
            ->getMock();
        $decodeArray = ['bin' => '123', 'currency' => 'PO', 'amount' => $amount];
        $fileDecode->expects(self::once())->method('decodeContent')->willReturn($decodeArray);

        $countryService = $this
            ->getMockBuilder(CountryService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $countryService->expects(self::once())->method('isValid')->willReturn(true);
        $countryService->expects(self::once())->method('isEuCountry')->willReturn($isEuCountry);

        $calcService = new CalculatorService($fileDecode, $countryService);
        $result = $calcService->calculate([json_encode(['bin' => '123', 'currency' => 'PO', 'amount' => $amount])],[]);
        self::assertSame($expected, $result[0]);
    }

    private function calculateDataProvider(): array
    {
        return [
            ['120.0', true, 1.2],
            ['120.0', true, 1.2],
            ['120.0', false, 2.4],
            ['123.123', false, 2.46246],
            ['123.123', true, 1.23123],
            ['0', true, 0],
            ['0', false, 0],
        ];
    }
}
