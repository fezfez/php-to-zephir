<?php

namespace PhpToZephir\Tests\Command\ConvertDirectoryFactory;

use PhpToZephir\Command\ConvertDirectoryFactory;
use Symfony\Component\Console\Output\NullOutput;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            '\PhpToZephir\Command\ConvertDirectory',
            ConvertDirectoryFactory::getInstance(new NullOutput())
        );
    }
}
