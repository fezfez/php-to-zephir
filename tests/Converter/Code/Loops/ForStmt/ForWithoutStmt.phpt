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
use PhpToZephir\Render\StringRender;
use PhpToZephir\CodeCollector\StringCodeCollector;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForWithoutStmt
{
    public function testSampleFromPhpDoc3()
    {
		$i = 1;
		for (; ; ) {
		    if ($i > 10) {
		        break;
		    }
		    echo $i;
		    $i++;
		}
    }
}
EOT;
$render = new StringRender();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	echo $render->render($file);
}

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class ForWithoutStmt
{
    public function testSampleFromPhpDoc3() -> void
    {
        var i;
    
        let i = 1;
        loop {
            
            if i > 10 {
                break;
            }
            echo i;
            let i++;
        
        }
    }

}