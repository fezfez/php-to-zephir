<?php

namespace Converter\Code\Method;

class AssignInFuncCallTest extends \ConverterBaseTest
{
    public function testWithAssignMethodReturn()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class AssignInFuncCall
{
    public function simpleTest()
    {
        $argument = "test";
        $defId = sprintf("%s", $id = $argument);
    }

    public function getDefinitionId($id)
    {
        return $id;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class AssignInFuncCall
{
    public function simpleTest() -> void
    {
        var argument, defId, id;
    
        let argument = "test";
        let id = argument;
        let defId =  sprintf("%s", id);
    }
    
    public function getDefinitionId(id)
    {
        
        return id;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testSimple()
    {
    	$php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class AssignInFuncCall
{
    public function simpleTest()
    {
        $argument = "test";
        sprintf("%s", $id = $argument);
    }
    
    public function getDefinitionId($id)
    {
        return $id;
    }
}
EOT;
    	$zephir = <<<'EOT'
namespace Code\Method;

class AssignInFuncCall
{
    public function simpleTest() -> void
    {
        var argument, id;
    
        let argument = "test";
        let id = argument;
        sprintf("%s", id);
    }
    
    public function getDefinitionId(id)
    {
        
        return id;
    }

}
EOT;
    	$this->assertConvertToZephir($php, $zephir);
    }
}
