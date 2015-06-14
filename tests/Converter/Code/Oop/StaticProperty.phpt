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

class StaticProperty
{
    public static $x;

    public static function test1()
    {
        Test::$x = 1;
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Oop;

class StaticProperty
{
    public static x;
    public static function test1() -> void
    {
        let Test::x = 1;
    }

}