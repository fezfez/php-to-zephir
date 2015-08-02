<?php

namespace Converter\Code\Condition\IfStmt;

class IfWithAssignementInConditionTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
    private $ignoredAnnotationNames = array();

    public function test($toto)
    {
        if ($averylongvariable = $toto) {
            echo 'tata';
        }
    }

    public function testWithConditionAndAssign($toto, $twoAssignedVariable, $treeAssignedVariable)
    {
        if ($toto === true && $twoAssignVariable = $twoAssignedVariable && $treeAssignVariable = $treeAssignedVariable) {
            echo 'tata';
        }
    }

    private function getName()
    {
        return 'myName';
    }

    public function testWithArrayDimAssign()
    {
        if (isset($this->ignoredAnnotationNames[$name = $this->getName()])) {
            return $this->ignoredAnnotationNames[$name];
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
    protected ignoredAnnotationNames = [];
    public function test(toto) -> void
    {
        var averylongvariable;
    
        let averylongvariable = toto;
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testWithConditionAndAssign(toto, twoAssignedVariable, treeAssignedVariable) -> void
    {
        var twoAssignVariable, treeAssignVariable;
    
        let twoAssignVariable = twoAssignedVariable;;
        let treeAssignVariable = treeAssignedVariable;
        if toto === true && twoAssignVariable {
            echo "tata";
        }
    }
    
    protected function getName()
    {
        
        return "myName";
    }
    
    public function testWithArrayDimAssign()
    {
        var name;
    
        let name =  this->getName();
        if isset this->ignoredAnnotationNames[name] {
            
            return this->ignoredAnnotationNames[name];
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
