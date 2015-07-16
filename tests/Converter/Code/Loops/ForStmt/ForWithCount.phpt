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

class ForWithCount
{
    public function testWithCall()
    {
        $myArray = array('test', '2');

        for ($i = 0; $i < count($myArray); $i++) {
			echo $i;
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class ForWithCount
{
    public function testWithCall() -> void
    {
        var myArray, i;
    
        
        let myArray =  ["test", "2"];
        for i in range(0, count(myArray)) {
            echo i;
        }
    }

}