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

namespace Code\StrongType\ReturnStmt;

class UndefinedTypeReturn
{
    public function test($toto)
    {
        return $toto;
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\StrongType\ReturnStmt;

class UndefinedTypeReturn
{
    public function test(toto)
    {
        
        return toto;
    }

}