<?php

namespace Code\Simple;

class ArrayDim
{
    private $targets = null;

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

	    $metadata['enum'][$property->name]['literal'] = ( ! empty($annotation->literal))
	    ? $annotation->literal
	    : $annotation->value;

	    $type = isset(self::$typeMap[$attribute->type])
	    ? self::$typeMap[$attribute->type]
	    : $attribute->type;


	   $alias = (false === $pos = strpos($name, '\\'))? $name : substr($name, 0, $pos);

	    if ($this->namespaces) {
	        echo 'toto';
	    } elseif (isset($this->imports[$loweredAlias = strtolower($alias)])) {
	        echo 'im converted !';
	    }

	    if ( ! $property = self::$annotationMetadata[$name]['default_property']) {

	    }

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