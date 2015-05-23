--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php
namespace Code\Loops\ForeachStmt;

class SimpleForeach
{
    public function test()
    {
        $myArray = array('test', '2');

        foreach ($myArray as $myValue) {
        }

        foreach ($myArray as $myKey => $myValue) {
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops\ForeachStmt;

class SimpleForeach
{
    public function test() -> void
    {
        var myArray, myValue, myKey;
    
        
        let myArray =  ["test", "2"];
        for myValue in myArray {
        }
        for myKey, myValue in myArray {
        }
    }

}