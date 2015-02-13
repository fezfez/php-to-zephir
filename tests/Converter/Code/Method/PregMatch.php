<?php

namespace Code\Method;

class PregMatch
{
    public function simpleTest()
    {
        $regex = '';
        $src = '';
        $matches = '';

        preg_match($regex, $src, $matches);
    }
}