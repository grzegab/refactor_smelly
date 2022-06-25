<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Service;

use App\Calculator\Domain\Service\BinListService;
use App\Calculator\Domain\Service\CountryService;
use PHPUnit\Framework\TestCase;

class CountryServiceTest extends TestCase
{
    /**
     * @dataProvider isValidDataProvider
     */
    public function testIsValid(bool $expected, ?string $input): void
    {
        $binList = $this
            ->getMockBuilder(BinListService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validation = (new CountryService($binList))->isValid($input);
        self::assertSame($expected, $validation);
    }

    /**
     * @dataProvider isEuCountryDataProvider
     */
    public function testIsEuCountry(bool $expected, string $input): void
    {
        $binList = $this
            ->getMockBuilder(BinListService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $binList->expects(self::once())->method('lookUpBinListCountry')->willReturn($input);

        $validation = (new CountryService($binList))->isEuCountry($input);
        self::assertSame($expected, $validation);
    }

    private function isValidDataProvider(): array
    {
        return [
            [true, 'abc'],
            [true, '1'],
            [true, '10000'],
            [true, '238742398724938742398743298423798432792384732'],
            [false, ''],
            [false, null],
            [false, '0'],
            [false, '-100'],
        ];
    }

    private function isEuCountryDataProvider(): array
    {
        return [
            [false, 'PL'], //this is sad :(
            [false, '123'],
            [false, ''],
            [false, '-120120aaaa'],
            [false, '1PO'],
            [false, 'PO1'],
            [true, 'po'],
            [true, 'it'],
            [true, 'AT'],
            [true, 'BE'],
            [true, 'BG'],
            [true, 'CY'],
            [true, 'CZ'],
            [true, 'DE'],
            [true, 'DK'],
            [true, 'EE'],
            [true, 'ES'],
            [true, 'FI'],
            [true, 'FR'],
            [true, 'GR'],
            [true, 'HR'],
            [true, 'HU'],
            [true, 'IE'],
            [true, 'IT'],
            [true, 'LT'],
            [true, 'LU'],
            [true, 'LV'],
            [true, 'MT'],
            [true, 'NL'],
            [true, 'PO'],
            [true, 'PT'],
            [true, 'RO'],
            [true, 'SE'],
            [true, 'SI'],
            [true, 'SK'],
        ];
    }
}
