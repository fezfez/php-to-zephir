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

namespace Code\Oop;

class DynamicInstance
{
    public function test()
    {
        $myClass = 'test';
        
        $myInstance = new $myClass();
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Oop;

class DynamicInstance
{
    public function test() -> void
    {
        var myClass, myInstance;
    
        let myClass = "test";
        let myInstance =  new {myClass}();
    }

}