<?php

namespace PhpToZephir\Tests\EngineFactory;

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
	public function testInstance()
	{
		$this->assertInstanceOf(
			'\PhpToZephir\Engine',
			EngineFactory::getInstance(
				new Logger(new NullOutput(), false)
			)
		);
	}
}
