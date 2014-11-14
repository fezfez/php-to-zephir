<?php

namespace Code\Condition\IfStmt;

class IfElseCondition
{
    private static $staticArray;

    public function test($toto)
    {
		if ($toto === 'tata') {
			echo 'tata';
		} elseif ($toto === 'tutu') {
			echo 'tutu';
		} else {
			echo 'else';
		}
    }

    public static function imStatic()
    {

    }

    public function testFuncCallIncondition()
    {
        if (isset($this->test('tata'))) {
            echo 'tutu';
        }

        if (isset(self::imStatic())) {
            echo 'static funcall!';
        }

        if (isset(self::$staticArray['test'])) {
            echo 'static array !';
        }
    }
}
