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
use PhpToZephir\Render\StringRender;
use PhpToZephir\CodeCollector\StringCodeCollector;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php
namespace Code\Simple;

class Cast
{
    public function test()
    {
        $maValue = '1';

        $maValue = (int) $maValue;
        $maValue = (double) $maValue;
        $maValue = (string) $maValue;
        $maValue = (array) $maValue;
        $maValue = (object) $maValue;
        $maValue = (bool) $maValue;
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
namespace Code\Simple;

class Cast
{
    public function test() -> void
    {
        var maValue;
    
        let maValue = "1";
        let maValue =  (int) maValue;
        let maValue =  (double) maValue;
        let maValue =  (string) maValue;
        let maValue =  (array) maValue;
        let maValue =  (object) maValue;
        let maValue =  (bool) maValue;
    }

}