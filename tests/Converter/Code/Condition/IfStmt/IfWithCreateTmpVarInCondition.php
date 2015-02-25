<?php

namespace Code\Condition\IfStmt;

class IfWithCreateTmpVarInCondition
{
    public function test($toto)
    {
        if (array() == $toto) {
            echo 'tata';
        }

        if ("im a string" == $toto) {
            echo 'tata';
        }

        if (false == $toto) {
            echo 'tata';
        }

        if ($toto === true && $toto === array(10)) {
            echo 'tata';
        }

        if ($toto === true && $toto === array() || $toto === false) {
            echo 'tata';
        }

        if ($toto === true && $toto === array() || $toto === false && $toto === true) {
            echo 'tata';
        }
    }
}
