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
namespace Code\Loops\ForStmt;

class SimpleFor
{
    public function test()
    {
        $myArray = array('test', '2');

        /*for ($i = 0; count($myArray) < $i; $i++) {

        }*/
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class SimpleFor
{
    public function test() -> void
    {
        var myArray;
    
        
        let myArray =  ["test", "2"];
    }

}