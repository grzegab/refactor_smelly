<?php

declare(strict_types=1);

namespace App\Calculator\Domain\Service;

use App\Helpers\DownloadContent;
use App\Helpers\FileDecode;

class RatesService
{
    private const RATE_URL = 'https://api.exchangeratesapi.io/latest';

    public function __construct(
        private readonly DownloadContent $downloadContent,
        private readonly FileDecode $fileDecode,
    )
    {
    }

    public function getRates(): array
    {
        $ratesRaw = $this->downloadContent->getFileContent(self::RATE_URL);

        return $this->fileDecode->decodeContent($ratesRaw);
    }
}