<?php

namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
    private $ignoredAnnotationNames = array();

    public function test($toto)
    {
		if ($averylongvariable = $toto) {
			echo 'tata';
		}
    }

    public function testWithConditionAndAssign($toto, $twoAssignedVariable, $treeAssignedVariable)
    {
    	if ($toto === true && $twoAssignVariable = $twoAssignedVariable && $treeAssignVariable = $treeAssignedVariable) {
    		echo 'tata';
    	}
    }

    private function getName()
    {
        return 'myName';
    }

    public function testWithArrayDimAssign()
    {
        if (isset($this->ignoredAnnotationNames[$name = $this->getName()])) {
            return $this->ignoredAnnotationNames[$name];
        }
    }
}
