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

class IfElseCondition
{
    private static $staticArray = array();

    public function test($toto)
    {
        $toReturn = null;

        if ($toto === 'tata') {
            $toReturn = 'tata';
        } elseif ($toto === 'tutu') {
            $toReturn = 'tutu';
        } else {
            $toReturn = 'else';
        }

        return $toReturn;
    }

    public static function imStatic()
    {
        return;
    }

    public function testFuncCallIncondition()
    {
        if ($this->test('tata')) {
            echo 'tutu';
        }

        if (self::imStatic()) {
            echo 'static funcall!';
        }

        if (isset(self::$staticArray['test'])) {
            echo 'static array !';
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Condition\IfStmt;

class IfElseCondition
{
    protected staticArray = [];
    public function test(toto)
    {
        var toReturn;
    
        let toReturn =  null;
        
        if toto === "tata" {
            let toReturn = "tata";
        } else { 
        
        if toto === "tutu" {
            let toReturn = "tutu";
        }
         else {
            let toReturn = "else";
        }}
        
        return toReturn;
    }
    
    public static function imStatic()
    {
        
        return;
    }
    
    public function testFuncCallIncondition() -> void
    {
        
        if this->test("tata") {
            echo "tutu";
        }
        
        if self::imStatic() {
            echo "static funcall!";
        }
        
        if isset this->staticArray["test"] {
            echo "static array !";
        }
    }

}