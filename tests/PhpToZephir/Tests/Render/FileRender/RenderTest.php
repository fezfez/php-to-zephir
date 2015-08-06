<?php

namespace PhpToZephir\Tests\Render\FileRender;

use PhpToZephir\Render\FileRender;

class RenderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $fileWriter = $this->getMock('PhpToZephir\FileWriter');

        $fileWriter->expects($this->once())
        ->method('write')
        ->with(array('zephir' => 'test'));

        $sUT = new FileRender($fileWriter);

        $sUT->render(array('zephir' => 'test'));
    }
}
