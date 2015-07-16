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

namespace Code\Method;

class TestUnset
{
    public function simpleTest()
    {
        $foo = 'simpleTest';

        unset($foo);
    }
}
EOT;
$render = new StringRender();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	echo $render->render($file);
}

?>
--EXPECT--
namespace Code\Method;

class TestUnset
{
    public function simpleTest() -> void
    {
        var foo;
    
        let foo = "simpleTest";
        unset foo;
    
    }

}