<?php

declare(strict_types=1);

namespace App\Tests\Calculator\App;

use App\Calculator\App\Service;
use App\Calculator\Domain\Service\CalculatorService;
use App\Calculator\Domain\Service\InputFileService;
use App\Calculator\Domain\Service\RatesService;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @dataProvider serviceDataProvider
     */
    public function testService(array $testArray): void
    {
        $inputFileService = $this
            ->getMockBuilder(InputFileService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ratesService = $this
            ->getMockBuilder(RatesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calculatorService = $this
            ->getMockBuilder(CalculatorService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calculatorService->expects(self::once())->method('calculate')->willReturn($testArray);

        $result = (new Service($inputFileService, $ratesService, $calculatorService))->calculateRates('aaa.txt');
        self::assertSame($testArray, $result);
    }

    private function serviceDataProvider(): array
    {
        return [
            [['a', 'b', 'c']],
            [[0, 1, 12321123321213213]],
            [[null, false, '12', '21432', '324.234']],
            [[12.21, -21312.123, 21321132312321231.321123, '-324.234']],
        ];
    }
}
