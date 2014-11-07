<?php

class Sample
{
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
}
