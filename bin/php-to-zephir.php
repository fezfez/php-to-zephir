<?php
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use PhpToZephir\Service\CliFactory;

chdir(realpath('./'));
if (is_file(__DIR__ . '/../vendor/autoload.php') === true) {
    include_once __DIR__ . '/../vendor/autoload.php';
} elseif (is_file(__DIR__ . '/../../../autoload.php') === true) {
    include_once __DIR__ . '/../../../autoload.php';
} else {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}


$output = new ConsoleOutput();
$input  = new ArgvInput();
$cli    = CliFactory::getInstance($output, $input);
$cli->run($input, $output);