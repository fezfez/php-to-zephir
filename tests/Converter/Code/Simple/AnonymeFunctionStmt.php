<?php
namespace Code\Simple;

class AnonymeFunction
{
	public function test($test)
	{
		return function($tutu) use ($test) {
			echo $tutu . $test;
		};
	}

	public function testIt()
	{
		$anonyme = $this->test('bar');

		$anonyme("foor");
	}
}