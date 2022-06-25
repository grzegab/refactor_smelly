<?php

declare(strict_types=1);

namespace App\Calculator\Domain\Service;

use App\Helpers\DownloadContent;
use App\Helpers\FileDecode;
use Generator;

class InputFileService
{
    public function __construct(
        private readonly DownloadContent $downloadContent,
        private readonly FileDecode $fileDecode,
    )
    {
    }

    public function getContent(string $inputFile): Generator
    {
        $fileInputContent = $this->downloadContent->getFileContent($inputFile);

        return $this->fileDecode->parseFileAddingBrackets($fileInputContent);
    }
}
