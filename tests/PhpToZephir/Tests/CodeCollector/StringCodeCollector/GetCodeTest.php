<?php

namespace PhpToZephir\Tests\CodeCollector\StringCodeCollector;

use PhpToZephir\CodeCollector\StringCodeCollector;

class GetCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $sUT = new StringCodeCollector(array('myStirng'));

        $this->assertEquals(array('myStirng'), $sUT->getCode());
    }
}
