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

class ForMinus
{
    public function testSimple()
    {
		for ($i = 0; $i < 10; $i++) {
			echo $i;
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class ForMinus
{
    public function testSimple() -> void
    {
        var i;
    
        for i in range(0, 9) {
            echo i;
        }
    }

}