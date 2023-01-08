<?php

namespace Molbal\AiPhpdoc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessFacade
{
    /**
     * @param mixed $filePath
     * @param OutputInterface $output
     * @return int
     */
    public function processFile(mixed $filePath, OutputInterface $output): int
    {
        try {
            $functions = FileParser::getFunctionsFromFile($filePath);
            $errors = 0;
            $completions = 0;
            foreach ($functions as $function) {
                if (!$function['phpdoc']) {
                    $output->writeln('Found function without docblock: ' . $function['name'] . '');
                    try {
                        $docs = DocumentationGenerator::createDocBlock($function['body']);
                        if (FileWriter::writeDocBlock($filePath, $function['body'], $docs)) {
                            $output->writeln('<info>Wrote docblock for ' . $function['name'] . '</info>');
                            $completions++;
                        } else {
                            if (FileWriter::writeDocBlock($filePath, $function['body'], $docs)) {
                                $output->writeln('<error>Generated docblock for ' . $function['name'] . ', but could not write it to the file.</error>');
                                $output->writeln($docs);
                                $errors++;
                            }
                        }
                    } catch (\Exception $error) {
                        $output->writeln('<error>Could not generate docblock for ' . $function['name'] . ': ' . $error->getMessage() . '</error>');
                    }
                }
            }

            if (empty($functions)) {
                $output->writeln('<comment>🙈 No functions found in the file.</comment>');
            }

            if ($completions > 0) {
                $output->writeln('Finished processing ' . $filePath . ' with ' . $completions . ' docblocks written and ' . $errors . ' errors.');
            }

            return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    public function processDirectory(string $directoryPath, bool $recursive, OutputInterface $output): int
    {
        $output->writeln('<comment>Processing directory: '.$directoryPath.'</comment>');
        $success = Command::SUCCESS;

        if (!is_dir($directoryPath)) {
            $output->writeln('<error>'.$directoryPath.' is not a valid directory.</error>');
            return Command::INVALID;
        }

        $iterator = new \RecursiveDirectoryIterator($directoryPath, \RecursiveDirectoryIterator::SKIP_DOTS);


        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $success = $this->processFile($file->getPathname(), $output) == Command::SUCCESS ? Command::SUCCESS : Command::FAILURE;
            }

            if ($file->isDir() && $recursive ) {
                $this->processDirectory($file->getPathname(), $recursive, $output);
            }
        }

        return $success;
    }

}