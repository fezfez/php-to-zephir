<?php

namespace Converter\Code\Condition\IfStmt;

class IfElseConditionTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
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

        $zephirExpected = <<<EOT
namespace Code\Condition\IfStmt;

class IfElseCondition
{
    protected static staticArray = [];
    public function test(toto)
    {
        var toReturn;
    
        let toReturn =  null;
        
        if toto === "tata" {
            let toReturn = "tata";
        } elseif toto === "tutu" {
            let toReturn = "tutu";
        } else {
            let toReturn = "else";
        }
        
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
        
        if isset self::staticArray["test"] {
            echo "static array !";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephirExpected);
    }
}
