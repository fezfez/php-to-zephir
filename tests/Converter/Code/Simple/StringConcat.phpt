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

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        $foo = "foo";
        $works = "bar".$foo."bar";

        return "bar".$foo."bar";
    }

    public function testConcatAndReturn()
    {
        return "bar $foo bar";
    }
}
EOT;
$render = new StringRender();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	echo $render->render($file);
}

?>
--EXPECT--
namespace Code\Simple;

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        var foo, works;
    
        let foo = "foo";
        let works =  "bar" . foo . "bar";
        
        return "bar" . foo . "bar";
    }
    
    public function testConcatAndReturn()
    {
        
        return "bar {foo} bar";
    }

}