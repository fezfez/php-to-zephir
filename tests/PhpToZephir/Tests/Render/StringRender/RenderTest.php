<?php

namespace PhpToZephir\Tests\Render\StringRender;

use PhpToZephir\Render\StringRender;

class RenderTest extends \PHPUnit_Framework_TestCase
{
	public function testInstance()
	{
		$sUT = new StringRender();
		
		$this->assertEquals('test', $sUT->render(array('zephir' => 'test')));
	}
}
