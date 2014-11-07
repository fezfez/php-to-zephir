<?php

use PhpToZephir\EngineFactory;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
	public function testConvertCode()
	{
		$this->convert(__DIR__ . '/code/');
	}

	private function convert($dir)
	{
		$engine = EngineFactory::getInstance();

		foreach (glob($dir . '*.php') as $file) {
			if (basename($file, '.php') !== 'IfWithAssignementInCondition') {
				continue;
			}

			$converted = $engine->convert(file_get_contents($file));

			file_put_contents(
				$dir . basename($file, '.php') . '.zep.log',
				$converted['code']
			);

			$this->assertStringEqualsFile(
				$dir . basename($file, '.php') . '.zep',
				$converted['code'],
				'test faile on file ' . $file
			);
		}

		$paths = glob($dir. '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
		foreach ($paths as $recursiveDir) {
			$this->convert($recursiveDir);
		}
	}
}
