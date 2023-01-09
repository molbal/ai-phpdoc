<?php

namespace Molbal\AiPhpdoc;

class DocumentationGenerator
{
    /**
     * createDocBlock
     *
     * @param string $function The function to generate the doc block for
     * 
     * @return string The generated doc block
     */
    public static function createDocBlock(string $function): string {
        $key = getenv('OPENAI_KEY');

        $openai = \OpenAI::client($key);

        $completion = $openai->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'Read the following PHP function: """ '.$function.' """ PHPDoc block for the function:',
            'max_tokens' => 1024,
            'stop' => ['"""'],
            'temperature' => 0.3
        ]);

        try {
            // Check if the OpenAI API returned an error response
            if (isset($completion['error'])) {
                throw new \RuntimeException($completion['error']);
            }

            return $completion['choices'][0]['text'];
        }
        catch (\Throwable $e) {
            throw new \RuntimeException('An error occurred while trying to get the doc block: ' . $e->getMessage());
        }
    }


}