<?php

namespace Code\Simple;

class ArrayDim
{
	public function testArrayDimScalarWithAssignLet()
	{
		$number = 0;
		$myArray = array(1 => 10);

		$myArray[1] = $myNumber++;
	}

	public function testArrayDimLetWithAssignScalar()
	{
	    $number = 0;
	    $myArray = array(1 => 10);

	    $myArray[$myNumber++] = 11;
	}

	public function testArrayDimLeftWithScalarAssignScalar()
	{
	    $number = 0;
	    $myArray = array(1 => array(2 => 10));

	     $myArray[$first++][$second++]['fezfez'][$three++] = $equals++;
	}

	public function testAssignLeftWithArrayDimLeftRight()
	{
	    $number = 0;
	    $myArray = array(1 => array(2 => 10));

	    $test = $myArray[$first++][$second++]['fezfez'][$three++];
	}

	public function testArrayDimLeftAssignArrayDimLet()
	{
	    $number = 0;
	    $myArray = array(1 => 10, 2 => 11);

	    $myArray[$myNumber++] = $myArray[$myNumber++];
	}

}