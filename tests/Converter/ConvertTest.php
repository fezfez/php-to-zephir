<?php

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertCode()
    {
        $this->convert(__DIR__ . '/code/');
    }

    private function convert($dir)
    {
        $engine = EngineFactory::getInstance(new Logger(new ConsoleOutput(), true));

        foreach ($engine->convertDirectory($dir, true, 'IfWithCreateTmpVarInCondition') as $file) {
            @mkdir(strtolower($file['destination']), 0777, true);
            file_put_contents(
                $file['fileDestination'],
                $file['zephir']
            );

            var_dump('write ' . $file['fileDestination']);
        }
    }
}
