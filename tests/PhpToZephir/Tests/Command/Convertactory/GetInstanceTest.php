<?php

namespace PhpToZephir\Tests\Command\ConvertFactory;

use PhpToZephir\Command\ConvertFactory;
use Symfony\Component\Console\Output\NullOutput;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            '\PhpToZephir\Command\Convert',
            ConvertFactory::getInstance(new NullOutput())
        );
    }
}
