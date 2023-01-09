<?php

namespace Molbal\AiPhpdoc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessDirectory extends Command
{
    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('dir')
            ->setDescription('Adds missing PHPDoc blocks to functions in a directory')
            ->addArgument('directory', InputArgument::OPTIONAL, 'The directory to iterate through. Defaults to `.`')
            ->addOption('recursive', 'r', InputOption::VALUE_NONE,'Iterate recusrively? Defaults to no');
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
        $dirPath = '.';
        try {
            $dirPath = $input->getArgument('directory');
        }
        catch (\Exception $ignored) {}
        $recursive = $input->getOption('recursive') !== false;
        return (new ProcessFacade())->processDirectory($dirPath, $recursive, $output);
    }
}