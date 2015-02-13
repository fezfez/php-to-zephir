<?php

namespace Code\Simple;

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        $foo = "foo";
        $works = "bar" . $foo . "bar";
        return "bar" . $foo . "bar";
    }

    public function testConcatAndReturn()
    {
        return "bar $foo bar";
    }
}