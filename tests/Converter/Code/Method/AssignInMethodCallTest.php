<?php

namespace Converter\Code\Method;

class AssignInMethodCallTest extends \ConverterBaseTest
{
    public function testWithAssignMethodReturn()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class AssignInMethodCall
{
    public function simpleTest()
    {
        $argument = "test";
        $defId = $this->getDefinitionId($id = $argument);
    }

    public function getDefinitionId($id)
    {
        return $id;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class AssignInMethodCall
{
    public function simpleTest() -> void
    {
        var argument, defId, id;
    
        let argument = "test";
        let id = argument;
        let defId =  this->getDefinitionId(id);
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
    
class AssignInMethodCall
{
    public function simpleTest()
    {
        $argument = "test";
        $this->getDefinitionId($id = $argument);
    }
    
    public function getDefinitionId($id)
    {
        return $id;
    }
}
EOT;
    	$zephir = <<<'EOT'
namespace Code\Method;

class AssignInMethodCall
{
    public function simpleTest() -> void
    {
        var argument, id;
    
        let argument = "test";
        let id = argument;
        this->getDefinitionId(id);
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
