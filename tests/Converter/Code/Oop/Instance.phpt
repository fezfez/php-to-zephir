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

class Instance
{
    public function test()
    {
        $myInstance = new StdClass();
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Oop;

class Instance
{
    public function test() -> void
    {
        var myInstance;
    
        let myInstance =  new StdClass();
    }

}