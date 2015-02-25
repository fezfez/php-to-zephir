<?php

namespace Code\Simple;

class SimpleAssign
{
    public function test()
    {
        $myString = 'foo';
        $myString .= 'bar';

        $myString &= 'test';

        $myNumber = 1;
        $myNumber += 2;
        $myNumber -= 1;
        $myNumber *= 2;
        $myNumber /= 2;
        $myNumber %= 2;
        $myNumber++;
        ++$myNumber;
        $myNumber--;
        --$myNumber;

        $result = 1 + $myNumber;
        $result = 1 * $myNumber;
        $result = 1 / $myNumber;
        $result = 1 % $myNumber;

        $superResult = $result.$myNumber;
    }
}
