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

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
