<?php

namespace Molbal\AiPhpdoc;

class FileWriter
{
    public static function writeDocBlock(string $file, string $body, string $docblock): bool
    {
        return file_put_contents(
            $file,
            str_replace($body, $docblock.PHP_EOL.$body, file_get_contents($file))
        ) !== false;
    }
}