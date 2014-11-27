<?php

namespace Code\Condition\IfStmt;

class IfWithCreateTmpVarInCondition
{
    public function test($toto)
    {
        // @FIXME tmp array
        if (array() == $toto) {
            echo 'tata';
        }

        if ("im a string" == $toto) {
            echo 'tata';
        }

        if (false == $toto) {
            echo 'tata';
        }

        if ($toto === true && $toto === array()) {
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
