<?php

namespace Converter\Code\Condition\IfStmt\IfWithAssignementArrayDimInCondition;

class IncrementInArrayDimTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt\IfWithAssignementArrayDimInCondition;

class IncrementInArrayDim
{
    public function testIncrementInArrayDim()
    {
        $i = 0;
        $toto = array(1 => true);

        if ($averylongvariable = $toto[$i++]) {
            echo 'tata';
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Condition\IfStmt\IfWithAssignementArrayDimInCondition;

class IncrementInArrayDim
{
    public function testIncrementInArrayDim() -> void
    {
        var i, toto, averylongvariable, tmpI1;
    
        let i = 0;
        let toto =  [1 : true];
        
        let i++;
        let tmpI1 = i;
        
        let averylongvariable = toto[tmpI1];
        if averylongvariable {
            echo "tata";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
