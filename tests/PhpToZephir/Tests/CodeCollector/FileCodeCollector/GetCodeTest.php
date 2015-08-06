<?php

namespace PhpToZephir\Tests\CodeCollector\FileCodeCollector;

use PhpToZephir\CodeCollector\FileCodeCollector;

class GetCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $sUT = new FileCodeCollector(array(__DIR__.'/test.zep'));

        $this->assertEquals(array(__DIR__.'/test.zep' => 'this is a test !'), $sUT->getCode());
    }
}
