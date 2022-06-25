<?php

declare(strict_types=1);

namespace App\Calculator\Domain\Service;

use App\Helpers\DownloadContent;
use App\Helpers\FileDecode;

class BinListService
{
    private const LOOKUP_URL = 'https://lookup.binlist.net';

    public function __construct(
        private readonly DownloadContent $downloadContent,
        private readonly FileDecode $fileDecode
    )
    {
    }

    public function lookUpBinListCountry(string $binValue)
    {
        $url = sprintf('%s/%s', self::LOOKUP_URL, $binValue);
        $binResults = $this->downloadContent->getFileContent($url);

        if (! $binResults) {
            throw new \RuntimeException('No bin list found');
        }

        $binListDecoded = $this->fileDecode->decodeContent($binResults);

        if (! $binListDecoded['country']['alpha2']) {
            throw new \RuntimeException('No bin list country found');
        }

        return $binListDecoded['country']['alpha2'];
    }
}