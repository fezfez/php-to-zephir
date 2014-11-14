<?php

namespace Code\TryCatch;

class SimpleTryCatch
{
	public function test()
	{
		try {
			echo 'try';
		} catch(Exception $e) {
			echo 'catsh';
		}
	}
}