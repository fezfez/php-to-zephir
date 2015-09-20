<?php

namespace PhpToZephir\Tests\Command\Convert;

use PhpToZephir\EngineFactory;
use PhpToZephir\Render\FileRender;
use PhpToZephir\FileWriter;
use PhpToZephir\Logger;
use PhpToZephir\Command\Convert;
use PhpToZephir\CodeCollector\DirectoryCodeCollector;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;

class ExecuteTest extends \PHPUnit_Framework_TestCase
{
    public function testFailOnDirectory()
    {
        $sUT = new Convert(
            EngineFactory::getInstance(),
            new FileRender(new FileWriter()),
            new NullOutput()
        );

        $this->setExpectedException('\InvalidArgumentException', '"not" is not a file or a directory');

        $sUT->execute(new ArrayInput(array('source' => 'not'), $sUT->getDefinition()), new NullOutput());
    }

    public function testOk()
    {
        $engine = $this->getMockBuilder('\PhpToZephir\Engine')
                     ->disableOriginalConstructor()
                     ->getMock();

        $fileRender = $this->getMockBuilder('\PhpToZephir\Render\FileRender')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new Convert(
            $engine,
            $fileRender,
            new NullOutput()
        );

        $engine->expects($this->once())
        ->method('convert')
        ->with(
            new DirectoryCodeCollector(array(__DIR__)),
            new Logger(new NullOutput(), null),
            null
        )
        ->willReturn(array(array('myReturn')));

        $fileRender->expects($this->once())
        ->method('render')
        ->with(array('myReturn'));

        $sUT->execute(new ArrayInput(array('source' => __DIR__), $sUT->getDefinition()), new NullOutput());
    }
}
