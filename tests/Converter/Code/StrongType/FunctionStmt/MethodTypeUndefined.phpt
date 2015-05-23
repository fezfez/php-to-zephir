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

namespace Code\StrongType\FunctionStmt;

class MethodTypeUndefined
{
    public function test($toto)
    {
        $test = 'tutu';
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\StrongType\FunctionStmt;

class MethodTypeUndefined
{
    public function test(toto) -> void
    {
        var test;
    
        let test = "tutu";
    }

}