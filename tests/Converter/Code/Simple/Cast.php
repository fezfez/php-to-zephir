<?php
namespace Code\Simple;

class Cast
{
    public function test()
    {
        $maValue = '1';

        $maValue = (int) $maValue;
        $maValue = (double) $maValue;
        $maValue = (string) $maValue;
        $maValue = (array) $maValue;
        $maValue = (object) $maValue;
        $maValue = (bool) $maValue;
    }
}
