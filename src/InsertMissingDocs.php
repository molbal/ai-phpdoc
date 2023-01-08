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
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The file to list the functions of');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        try {
            $functions = FileParser::getFunctionsFromFile($filePath);
            foreach ($functions as $function) {
                $output->writeln($function['name'] . ': ' . ($function['phpdoc'] ? 'yes' : 'no'));
            }
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
