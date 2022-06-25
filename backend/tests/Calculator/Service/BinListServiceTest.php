<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Service;

use App\Calculator\Domain\Service\BinListService;
use App\Helpers\DownloadContent;
use App\Helpers\FileDecode;
use PHPUnit\Framework\TestCase;

class BinListServiceTest extends TestCase
{
    /**
     * @dataProvider lookupDataProvider
     */
    public function testLookUpBinListCountry(string $country): void
    {
        $content = $this
            ->getMockBuilder(DownloadContent::class)
            ->disableOriginalConstructor()
            ->getMock();
        $content->expects(self::once())->method('getFileContent')->willReturn('aaaa');

        $decode = $this
            ->getMockBuilder(FileDecode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $array = [];
        $var['alpha2'] = $country;
        $array['country'] = $var;

        $decode->expects(self::once())->method('decodeContent')->willReturn($array);
        $result = (new BinListService($content, $decode))->lookUpBinListCountry($country);
        self::assertSame($country, $result);
    }

    private function lookupDataProvider(): array
    {
        return [
            ['PO'],
            ['SK'],
            ['AT'],
        ];
    }
}