<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Service;

use App\Calculator\Domain\Service\RatesService;
use App\Helpers\DownloadContent;
use App\Helpers\FileDecode;
use PHPUnit\Framework\TestCase;

class RatesServiceTest extends TestCase
{
    /**
     * @dataProvider serviceDataProvider
     */
    public function testGetRates(array $testArray): void
    {
        $content = $this
            ->getMockBuilder(DownloadContent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decode = $this
            ->getMockBuilder(FileDecode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $decode->expects(self::once())->method('decodeContent')->willReturn($testArray);

        $rates = (new RatesService($content, $decode))->getRates();
        self::assertSame($testArray, $rates);
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