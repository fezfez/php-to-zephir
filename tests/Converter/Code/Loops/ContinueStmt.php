<?php
namespace Code\Loops;

class ContinueStmt
{
	public function test()
	{
		$tests = array('im a test');

		foreach ($tests as $test) {
			continue;
		}

		foreach ($tests as $test) {
		    continue 1;
		}
	}
}