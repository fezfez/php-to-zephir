<?php

namespace PhpToZephir\Tests\Command\ConvertDirectoryFactory;

use PhpToZephir\EngineFactory;
use PhpToZephir\Render\FileRender;
use PhpToZephir\FileWriter;
use PhpToZephir\Command\ConvertDirectory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;

class ExecuteTest extends \PHPUnit_Framework_TestCase
{
    public function testFailOnDirectory()
    {
        $sUT = new ConvertDirectory(
            EngineFactory::getInstance(),
            new FileRender(new FileWriter()),
            new NullOutput()
        );

        $this->setExpectedException('\InvalidArgumentException');

        $sUT->execute(new ArrayInput(array('dir' => 'not')), new NullOutput());
    }
}
