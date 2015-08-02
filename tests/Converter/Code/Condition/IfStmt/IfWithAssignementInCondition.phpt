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
$render = new StringRender();
$codeValidator = new PhpToZephir\CodeValidator();

foreach ($engine->convert(new StringCodeCollector(array($code))) as $file) {
	$zephir = $render->render($file);
	$codeValidator->isValid($zephir);
	
	echo $zephir;
}

?>
--EXPECT--
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