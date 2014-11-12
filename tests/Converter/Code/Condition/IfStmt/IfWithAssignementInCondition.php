<?php

namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
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
}
