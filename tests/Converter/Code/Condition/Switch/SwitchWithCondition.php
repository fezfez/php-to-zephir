<?php

class Sample
{
    public function test($toto)
    {
		switch (true) {
			case is_array($toto):
				echo 'array';
				break;
			case is_bool($toto) === true:
				echo 'bool';
				break;
			case is_dir($toto):
			case is_file($toto):
			case is_executable($toto):
				echo 'filesysteme';
				break;
			default:
				echo 'what do you mean ?';
				break;
		}
    }

    public function testWithFirstWithoutStmt($toto)
    {
    	switch (true) {
    		case is_array($toto):
    		case is_bool($toto) === true:
    		case is_dir($toto):
    		case is_file($toto):
    		case is_executable($toto):
    			echo 'filesysteme';
    			break;
    		default:
    			echo 'what do you mean ?';
    			break;
    	}
    }
}
