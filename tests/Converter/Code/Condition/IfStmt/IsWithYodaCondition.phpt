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

namespace Code\Condition\IfStmt;

class IsWithYodaCondition
{
    public function test($toto)
    {
        if ('tata' === $toto) {
            echo 'tata';
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Condition\IfStmt;

class IsWithYodaCondition
{
    public function test(toto) -> void
    {
        
        if toto === "tata" {
            echo "tata";
        }
    }

}