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
$render = new StringRender();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	echo $render->render($file);
}

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