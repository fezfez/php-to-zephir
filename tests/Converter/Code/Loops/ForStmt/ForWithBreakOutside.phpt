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

class ForWithBreakOutside
{
    public function testSampleFromPhpDoc2()
    {
		for ($i = 1; ; $i++) {
		    if ($i > 10) {
		        break;
		    }
		    echo $i;
		}
	}
}
EOT;
$render = new StringRender();
$codeValidator = new PhpToZephir\CodeValidator();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	$zephir = $render->render($file);
	$codeValidator->isValid($zephir);
	
	echo $zephir;
}

?>
--EXPECT--
namespace Code\Loops\ForStmt;

class ForWithBreakOutside
{
    public function testSampleFromPhpDoc2() -> void
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