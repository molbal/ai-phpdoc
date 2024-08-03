<?php

namespace Molbal\AiPhpdoc;

class FileWriter
{
    /**
     * 
     * Write a docblock to a given function
     * 
     * @param string $file The file to write the docblock to
     * @param string $body The body of the function
     * @param string $docblock The docblock to write
     * 
     * @return bool True if the docblock was written successfully, false otherwise
     */
    public function writeDocBlock(string $file, string $body, string $docblock): bool {

        $docblock = $this->cleanDocBlock($docblock);

        $body = rtrim(explode('{', $body, 2)[0]);
        $docblock = $this->indentDocBlock(trim($docblock), $this->getLeadingWhitespace($body));
        $originalContents = file_get_contents($file);
        $newContents = str_replace($body, $docblock . PHP_EOL. ltrim($body, PHP_EOL), $originalContents, $c);
        file_put_contents($file, $newContents);
        return  $c == 1;
    }
    /**
     * Retrieves the leading whitespace from a string.
     *
     * @param string $str The string to parse.
     *
     * @return string The leading whitespace from the string.
     */

    private function getLeadingWhitespace(string $str): string {
        $matches = [];
        $whitespace = preg_match('/^\s+/', $str, $matches) ? $matches[0] : '';
        return str_replace(PHP_EOL, '', $whitespace);
    }

    /**
     * Indents a DocBlock string with the given whitespace.
     *
     * @param string $docs The DocBlock string to indent.
     * @param string $whitespace The whitespace to use for indentation.
     *
     * @return string The indented DocBlock string.
     */
    private function indentDocBlock(string $docs, string $whitespace): string
    {
        $lines = explode(PHP_EOL, $docs);
        $modifiedLines = array_map(function($line) use ($whitespace) {
            return $whitespace . $line;
        }, $lines);
        return implode(PHP_EOL, $modifiedLines);
    }


    /**
     * Clean a doc block from a given string.
     * 
     * @param string $str The string to clean.
     * 
     * @return string The cleaned doc block.
     */
    private function cleanDocBlock(string $str): string {
        $startPos = strpos($str, '/**');
        $endPos = strpos($str, '*/');
        if ($startPos === false || $endPos === false) {
            return '';
        }
        $startPos += 3;
        return PHP_EOL.'/**'.PHP_EOL.trim(substr($str, $startPos, $endPos - $startPos)).PHP_EOL.'*/';
    }
}