<?php

namespace Molbal\AiPhpdoc;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessFile extends Command
{

    /**
     * Configures the current command.
     *
     * @param string $file The file to list the functions of
     */
    protected function configure()
    {
        $this
            ->setName('file')
            ->setDescription('Adds missing PHPDoc blocks to functions in a file')
            ->addArgument('file', InputArgument::REQUIRED, 'The file to process.');
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
        return (new ProcessFacade())->processFile($filePath, $output);
    }
}
