<?php

namespace Molbal\AiPhpdoc;

class FileWriter
{
    public function writeDocBlock(string $file, string $body, string $docblock): bool {
        $body = rtrim(explode('{', $body, 2)[0]);
        $docblock = $this->indentDocBlock(trim($docblock), $this->getLeadingWhitespace($body));
        $originalContents = file_get_contents($file);
        $newContens = str_replace($body, $docblock . PHP_EOL . $body, $originalContents, $c);
        file_put_contents($file, $newContens);
        return  $c == 1;
    }

    private function getLeadingWhitespace(string $str) {
        $matches = [];
        $whitespace = preg_match('/^\s+/', $str, $matches) ? $matches[0] : '';
        return str_replace(PHP_EOL, '', $whitespace);
    }


    private function indentDocBlock(string $docs, string $whitespace) {
        $lines = explode(PHP_EOL, $docs);
        $modifiedLines = array_map(function($line) use ($whitespace) {
            return $whitespace . $line;
        }, $lines);
        return implode(PHP_EOL, $modifiedLines);
    }
}