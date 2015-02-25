<?php

namespace Code\Method;

class MethodExist
{
    public function simpleTest()
    {
        $foo = 'simpleTest';

        method_exists(self, $foo);
    }
}
