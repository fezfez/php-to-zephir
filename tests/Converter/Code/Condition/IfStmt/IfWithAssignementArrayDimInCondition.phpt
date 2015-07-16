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

namespace Code\Condition\IfStmt;

class IfWithAssignementArrayDimInCondition
{
    public function test()
    {
        $toto = array(1 => true);

        if ($averylongvariable = $toto[1]) {
            echo 'tata';
        }
    }

    public function testIncrementInArrayDim()
    {
        $i = 0;
        $toto = array(1 => true);

        // @FIXME the i++ is extract twice
        if ($averylongvariable = $toto[$i++]) {
            echo 'tata';
        }
    }
}
EOT;
$render = new StringRender();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	echo $render->render($file);
}

?>
--EXPECT--
namespace Code\Condition\IfStmt;

class IfWithAssignementArrayDimInCondition
{
    public function test() -> void
    {
        var toto, averylongvariable;
    
        
        let toto =  [1 : true];
        let averylongvariable = toto[1];
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testIncrementInArrayDim() -> void
    {
        var i, toto, averylongvariable;
    
        let i = 0;
        
        let toto =  [1 : true];
        // @FIXME the i++ is extract twice
        let i++;;
        var tmpArray;
        let i++;
        let averylongvariable = toto[i];
        if averylongvariable {
            echo "tata";
        }
    }

}