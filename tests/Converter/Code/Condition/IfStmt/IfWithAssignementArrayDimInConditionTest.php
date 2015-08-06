<?php

namespace Converter\Code\Condition\IfStmt;

class IfWithAssignementArrayDimInConditionTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
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
        $zephir = <<<'EOT'
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
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
