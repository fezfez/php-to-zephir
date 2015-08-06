<?php

namespace PhpToZephir\Tests\Command\ConvertDirectory;

use PhpToZephir\EngineFactory;
use PhpToZephir\Render\FileRender;
use PhpToZephir\FileWriter;
use PhpToZephir\Logger;
use PhpToZephir\Command\ConvertDirectory;
use PhpToZephir\CodeCollector\DirectoryCodeCollector;
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

        $this->setExpectedException('\InvalidArgumentException', 'Directory "not" does not exist');

        $sUT->execute(new ArrayInput(array('dir' => 'not'), $sUT->getDefinition()), new NullOutput());
    }

    public function testOk()
    {
        $engine = $this->getMockBuilder('\PhpToZephir\Engine')
                     ->disableOriginalConstructor()
                     ->getMock();

        $fileRender = $this->getMockBuilder('\PhpToZephir\Render\FileRender')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ConvertDirectory(
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

        $sUT->execute(new ArrayInput(array('dir' => __DIR__), $sUT->getDefinition()), new NullOutput());
    }
}
