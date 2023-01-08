<?php

namespace Molbal\AiPhpdoc;

use Exception;

class FileParser
{
    /**
     * Extracts a list of functions from a string containing PHP code.
     *
     * @param string $code The string of PHP code.
     * @return array An array of functions, each of which is an associative array with the following keys:
     *   - name: the name of the function
     * @throws Exception if the file does not exist
     */
    public static function getFunctionsFromString(string $code): array {

        $functions = [];
        $matches = [];

        preg_match_all('/^\s*(\/\*\*.*?\*\/)?\s*(?:(?:public|private|protected)\s+)?(?:static\s+)?function\s+(\w+)\s*\(([^)]*)\)\s*(?:\:\s([^\s]+))?\s*\{(.*?)^\s*\}/ms', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $functions[] = [
                'name' => $match[2],
                'phpdoc' => self::getPhpdocFromString($match[0]),
                'body' => $match[0],
            ];
        }
        return $functions;
    }


    /**
     * Extracts a list of phpdocs from a string containing PHP code. The function works by using regular expressions.
     *
     * @param string $code The string of PHP code containing a function with.
     * @return ?string null, if the string contains no PHPDocs block, or the phpdocs comment block, if it contains one:
     **/
    public static function getPhpDocFromString(string $code): ?string {
        $matches = [];
        preg_match('/^\s*\/\*\*(.*?)\*\//ms', $code, $matches);
        return $matches[0] ?? null;
    }

    /**
     * Extracts a list of functions from a PHP file.
     *
     * @param string $filePath The file path of the PHP file.
     * @return array An array of functions, each of which is an associative array with the following keys:
     *   - name: the name of the function
     *   - hasDocComment: a boolean indicating whether the function has a PHPDoc block or not
     *   - body: the body of the function as a string
     * @throws Exception if the file does not exist
     */
    public static function getFunctionsFromFile(string $filePath): array {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: $filePath");
        }
        $code = file_get_contents($filePath);
        return self::getFunctionsFromString($code);
    }

}