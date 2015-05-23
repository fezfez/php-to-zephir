--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php
namespace Code\Loops;

class ContinueStmt
{
    public function test()
    {
        $tests = array('im a test');

        foreach ($tests as $test) {
            continue;
        }

        foreach ($tests as $test) {
            continue 1;
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops;

class ContinueStmt
{
    public function test() -> void
    {
        var tests, test;
    
        
        let tests =  ["im a test"];
        for test in tests {
            continue;
        }
        for test in tests {
            continue;
        }
    }

}