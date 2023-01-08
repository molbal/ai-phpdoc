<?php

namespace Molbal\AiPhpdoc;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'aiphpdocs:generate',
    description: 'Inserts missing PHPDoc blocks in a file.',
    aliases: [],
    hidden: false
)]
class InsertMissingDocs extends Command
{


    /**
     * Configures the current command.
     *
     * @param string $file The file to list the functions of
     */
    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The file to list the functions of');
    }


    /**
     * Execute the function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        try {
            $functions = FileParser::getFunctionsFromFile($filePath);
            $errors = 0;
            $completions = 0;
            foreach ($functions as $function) {
                if (!$function['phpdoc']) {
                    $output->writeln('Found function without docblock: '.$function['name'].'');
                    try {
                        $docs = DocumentationGenerator::createDocBlock($function['body']);
                        if (FileWriter::writeDocBlock($filePath, $function['body'], $docs)) {
                            $output->writeln('<info>Wrote docblock for '.$function['name'].'</info>');
                            $completions++;
                        }
                        else {
                            if (FileWriter::writeDocBlock($filePath, $function['body'], $docs)) {
                                $output->writeln('<error>Generated docblock for '.$function['name'].', but could not write it to the file.</error>');
                                $output->writeln($docs);
                                $errors++;
                            }
                        }
                    }
                    catch (\Exception $error) {
                        $output->writeln('<error>Could not generate docblock for '.$function['name'].': '.$error->getMessage().'</error>');
                    }
                }
            }

            if (empty($functions)) {
                $output->writeln('<comment>🙈 No functions found in the file.</comment>');
            }

            if ($completions > 0) {
                $output->writeln('Finished processing '.$filePath.' with '.$completions.' docblocks written and '.$errors.' errors.');
            }

            return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
