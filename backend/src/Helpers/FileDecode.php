<?php

declare(strict_types=1);

namespace App\Helpers;

use Generator;
use RuntimeException;

class FileDecode
{
    public function parseFileAddingBrackets($fileContent): Generator
    {
        foreach (explode("\n", $fileContent) as $row) {
            yield '[' . $row . ']';
        }
    }

    public function decodeContent(string $json, bool $getFirst = false): array
    {
        $content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if ($getFirst) {
            return $content[0];
        }

        return $content;
    }
}
