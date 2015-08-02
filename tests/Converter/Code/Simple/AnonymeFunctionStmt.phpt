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

class AnonymeFunctionStmt
{
    public function test($test)
    {
        return function ($tutu) use ($test) {
            echo $tutu.$test;
        };
    }

    public function testIt()
    {
        $anonyme = $this->test('bar');

        $anonyme("foor");
    }
}

EOT;
$render = new StringRender();
$codeValidator = new PhpToZephir\CodeValidator();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $i => $file) {
	$zephir = $render->render($file);
	$codeValidator->isValid($zephir);
	
	echo $zephir;
}

?>
--EXPECT--
namespace Code\Simple;

class AnonymeFunctionStmt
{
    public function test(test)
    {
        
        return new AnonymeFunctionStmttestClosureOne(test);
    }
    
    public function testIt() -> void
    {
        var anonyme;
    
        let anonyme =  this->test("bar");
        {anonyme}("foor");
    }

}namespace Code\Simple;

class AnonymeFunctionStmttestClosureZero
{
    private test;

    public function __construct(test)
    {
                let this->test = test;

    }

    public function __invoke(tutu)
    {
    echo tutu . this->test;
    }
}
    
