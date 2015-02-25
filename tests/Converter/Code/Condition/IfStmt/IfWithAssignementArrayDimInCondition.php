<?php

namespace Code\Condition\IfStmt;

class IfWithAssignementArrayDimInCondition
{
    public function test()
    {
        $toto = array(1 => true);

        if ($averylongvariable = $toto[1]) {
            echo 'tata';
        }
    }

    public function testIncrementInArrayDim()
    {
        $i = 0;
        $toto = array(1 => true);

        // @FIXME the i++ is extract twice
        if ($averylongvariable = $toto[$i++]) {
            echo 'tata';
        }
    }
}
