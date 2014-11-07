<?php

class Sample
{
    public function test($toto)
    {
		if ($averylongvariable = $toto) {
			echo 'tata';
		}
    }

    public function testWithConditionAndAssign($toto)
    {
    	if ($toto === true && $averylongvariable = $toto && $tata = $toto) {
    		echo 'tata';
    	}
    }
}
