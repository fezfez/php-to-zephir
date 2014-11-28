<?php

namespace Code\Simple;

class ArrayDim
{
    private $targets = null;
    private $name = 'toto';
    private $literal = null;
    private $value = 'tutu';
    private $type = 'test';
    private $typeMap = array();
    private $namespaces = null;
    private $imports = array();
    private static $annotationMetadata = array('test');

	public function testArrayDimScalarWithAssignLet()
	{
		$number = 0;
		$myArray = array(1 => 10);

		$myArray[1] = $number++;
	}

	public function testArrayDimLetWithAssignScalar()
	{
	    $number = 0;
	    $myArray = array(1 => 10);

	    $myArray[$number++] = 11;
	}

	public function testArrayDimLeftWithScalarAssignScalar()
	{
	    $number = 0;
	    $myArray = array(1 => array(2 => 10));

	    $myArray[$number++][$number++]['fezfez'][$number++] = $number++;
	}

	public function testAssignLeftWithArrayDimLeftRight()
	{
	    $number = 0;
	    $myArray = array(1 => array(2 => 10));

	    $test = $myArray[$number++][$number++]['fezfez'][$number++];
	}

	public function testArrayDimLeftAssignArrayDimLet()
	{
	    $number = 0;
	    $myArray = array(1 => 10, 2 => 11);

	    $myArray[$number++] = $myArray[$number++];
	}

	private function getConstructor()
	{
	    return null;
	}

	private function getNumberOfParameters()
	{
	    return 5;
	}

	public function arrayDimAssignObjectPropertie()
	{
	    $metadata = array();
	    $test = true;

	    $metadata['tutu'] = $test;
	    $metadata['targets'] = $this->targets;
	    $metadata['properties'][$this->name] = $this->name;

	    $metadata['enum'][$this->name]['literal'] = ( ! empty($this->literal))
	    ? $this->literal
	    : $this->value;

	    $type = isset($this->typeMap[$this->type])
	    ? $this->typeMap[$this->type]
	    : $this->type;


	    $name = "test";

	    $alias = (false === $pos = strpos($name, '\\'))? $name : substr($name, 0, $pos);

	    if ($this->namespaces) {
	        echo 'toto';
	    } elseif (isset($this->imports[$loweredAlias = strtolower($alias)])) {
	        echo 'im converted !';
	    }

	    if ( ! $property = self::$annotationMetadata[$name]['default_property']) {

	    }

	    $lineCnt = 0;
	    $lineNumber = 1;
	    if ($lineCnt++ == $lineNumber) {

	    }
	}

	public function declareArrayWithAssignInTernaryOperation()
	{
	    $metadata = array(
	        'default_property' => null,
	        'has_constructor'  => (null !== $constructor = $this->getConstructor()) && $this->getNumberOfParameters() > 0,
	    );
	}

}