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
echo $engine->convertString($code, true);

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
        
        return foo . "bar" . "bar";
    }
    
    public function testConcatAndReturn()
    {
        
        return "bar {foo} bar";
    }

}