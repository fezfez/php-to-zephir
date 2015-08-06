<?php

namespace PhpToZephir\Tests\Service\CliFactory;

use PhpToZephir\Service\CliFactory;
use Symfony\Component\Console\Output\NullOutput;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf('\Symfony\Component\Console\Application', CliFactory::getInstance(new NullOutput()));
    }
}
