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

class ForEquals
{
    public function testSampleFromPhpDoc1()
    {
	    for ($i = 1; $i <= 10; $i++) {
		    echo $i;
		}
	}
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class ForEquals
{
    public function testSampleFromPhpDoc1() -> void
    {
        var i;
    
        for i in range(1, 10) {
            echo i;
        }
    }

}