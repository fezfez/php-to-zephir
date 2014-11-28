<?php

namespace Code\Condition\IfStmt;

class IfElseCondition
{
    private static $staticArray = array();

    public function test($toto)
    {
        $toReturn = null;

		if ($toto === 'tata') {
			$toReturn ='tata';
		} elseif ($toto === 'tutu') {
			$toReturn = 'tutu';
		} else {
			$toReturn = 'else';
		}

		return $toReturn;
    }

    public static function imStatic()
    {
        return null;
    }

    public function testFuncCallIncondition()
    {
        if ($this->test('tata')) {
            echo 'tutu';
        }

        if (self::imStatic()) {
            echo 'static funcall!';
        }

        if (isset(self::$staticArray['test'])) {
            echo 'static array !';
        }
    }
}
