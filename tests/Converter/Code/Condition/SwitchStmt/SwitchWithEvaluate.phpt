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
use PhpToZephir\Render\StringRender;
use PhpToZephir\CodeCollector\StringCodeCollector;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php

namespace Code\Condition\SwitchStmt;

class SwitchWithEvaluate
{
    public function test($toto)
    {
        switch ($toto) {
            case "{":
                echo 'array';
                break;
            case "]":
                echo 'bool';
                break;
            case "|":
            case "-":
            case "5":
                echo 'filesysteme';
                break;
            default:
                echo 'what do you mean ?';
                break;
        }
    }
}
EOT;
$render = new StringRender();
$codeValidator = new PhpToZephir\CodeValidator();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	$zephir = $render->render($file);
	$codeValidator->isValid($zephir);
	
	echo $zephir;
}

?>
--EXPECT--
namespace Code\Condition\SwitchStmt;

class SwitchWithEvaluate
{
    public function test(toto) -> void
    {
        switch (toto) {
            case "{":
                echo "array";
                break;
            case "]":
                echo "bool";
                break;
            case "|":
            case "-":
            case "5":
                echo "filesysteme";
                break;
            default:
                echo "what do you mean ?";
                break;
        }
    }

}