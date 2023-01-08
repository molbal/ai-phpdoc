<?php

namespace Molbal\AiPhpdoc;

use Exception;

class FileParser
{
    /**
     * Extracts a list of functions from a string containing PHP code.
     *
     * @param string $filePath The PHP code.
     * @return array An array of functions, each of which is an associative array with the following keys:
     *   - name: the name of the function
     *   - hasDocComment: a boolean indicating whether the function has a PHPDoc block or not
     *   - body: the body of the function as a string
     */
    public static function getFunctionsFromString(string $code): array {
        $functions = array();
        $tokens = token_get_all($code);
        $currentFunction = null;
        $docComment = null;
        $openBraces = 0;
        $functionBody = '';
        foreach ($tokens as $token) {
            if (is_array($token)) {
                switch ($token[0]) {
                    case T_FUNCTION:
                        $currentFunction = array(
                            'name' => null,
                            'hasDocComment' => false,
                            'body' => '',
                        );
                        break;
                    case T_STRING:
                        if ($currentFunction !== null) {
                            $currentFunction['name'] = $token[1];
                        }
                        break;
                    case T_DOC_COMMENT:
                        $docComment = $token[1];
                        break;
                }
            } else {
                if ($currentFunction !== null) {
                    if ($token === '{') {
                        $openBraces++;
                    } elseif ($token === '}') {
                        $openBraces--;
                    }
                    $functionBody .= $token;
                }
                if ($token === '{' && $currentFunction !== null && $openBraces === 0) {
                    if ($docComment !== null) {
                        $currentFunction['hasDocComment'] = true;
                        $docComment = null;
                    }
                    $currentFunction['body'] = $functionBody;
                    $functions[] = $currentFunction;
                    $currentFunction = null;
                    $functionBody = '';
                }
            }
        }
        return $functions;
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
        return self::getFunctions($code);
    }

}