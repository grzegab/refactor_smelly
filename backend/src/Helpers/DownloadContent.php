<?php

declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

class DownloadContent
{
    public function getFileContent(string $file): string
    {
        return file_get_contents($file);
    }
}
