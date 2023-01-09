<?php
require __DIR__.'/../vendor/autoload.php';

use Molbal\AiPhpdoc\ProcessDirectory;
use Molbal\AiPhpdoc\ProcessFile;
use Symfony\Component\Console\Application;

$application = new Application('AI-PHPDocs by molbal', '1.0.5');

$application->add(new ProcessFile());
$application->add(new ProcessDirectory());

$application->run();

