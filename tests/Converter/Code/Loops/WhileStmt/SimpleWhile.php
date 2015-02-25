<?php
namespace Code\Loops\WhileStmt;

class SimpleWhile
{
    public function test()
    {
        while (true) {
            break;
        }
    }

    public function whileWithAssign()
    {
        $pos = 0;
        $input = 'mySuperString';

        /*while (($pos = strpos($input, '@', $pos)) !== false) {

        }*/
    }
}
