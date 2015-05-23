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

namespace Code\Method;

class PregMatch
{
    public function simpleTest()
    {
        $regex = '';
        $src = '';
        $matches = '';

        preg_match($regex, $src, $matches);
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Method;

class PregMatch
{
    public function simpleTest() -> void
    {
        var regex, src, matches;
    
        let regex = "";
        let src = "";
        let matches = "";
        preg_match(regex, src, matches);
    }

}