<?php

namespace Molbal\AiPhpdoc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends Command
{
    protected static $defaultName = 'aiphpdocs';

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
                $output->writeln($function['name'] . ': ' . ($function['hasDocComment'] ? 'yes' : 'no'));
            }
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
        }
    }
}
