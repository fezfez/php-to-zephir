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

namespace Code\TryCatch;

class SimpleTryCatch
{
    public function test()
    {
        try {
            echo 'try';
        } catch (Exception $e) {
            echo 'catsh';
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\TryCatch;

class SimpleTryCatch
{
    public function test() -> void
    {
        var e;
    
        try {
            echo "try";
        } catch Exception, e {
            echo "catsh";
        }
    }

}