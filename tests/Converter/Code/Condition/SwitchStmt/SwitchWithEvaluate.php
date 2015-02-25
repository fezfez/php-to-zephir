<?php

namespace Code\Condition\SwitchStmt;

class SwitchWithEvaluate
{
    public function test($toto)
    {
        switch ($toto) {
            case "{":
                echo 'array';
                break;
            case "]":
                echo 'bool';
                break;
            case "|":
            case "-":
            case "5":
                echo 'filesysteme';
                break;
            default:
                echo 'what do you mean ?';
                break;
        }
    }
}
