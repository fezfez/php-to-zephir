<?php

namespace PhpToZephir\Tests\CodeCollector\DirectoryCodeCollector;

use PhpToZephir\CodeCollector\DirectoryCodeCollector;

class GetCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $sUT = new DirectoryCodeCollector(array(__DIR__.'/myDirTest/'));

        $this->assertEquals(
            array(
                __DIR__.'/myDirTest/afile.php' => 'a file',
                __DIR__.'/myDirTest/recursive/recursive.php' => 'recursive!',
            ),
            $sUT->getCode()
        );
    }
}
