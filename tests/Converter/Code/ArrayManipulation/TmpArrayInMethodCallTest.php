<?php

namespace Converter\ArrayManipulation;

class TmpArrayInMethodCallTest extends \ConverterBaseTest
{
    public function testSimpleTmpArray()
    {
        $php = <<<'EOT'
<?php

namespace Code\ArrayManipulation;

class SimpleTmpArray
{
    public function test()
    {
        $container = 'test';
        $configurator = $this->inlineArguments($container, array($this->getConfigurator()));
    }

    public function getConfigurator()
    {
        return true;
    }

    public function inlineArguments($container, $configurator)
    {
        return true;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class SimpleTmpArray
{
    public function test() -> void
    {
        var container, configurator;
    
        let container = "test";
        let configurator =  this->inlineArguments(container, [this->getConfigurator()]);
    }
    
    public function getConfigurator()
    {
        
        return true;
    }
    
    public function inlineArguments(container, configurator)
    {
        
        return true;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testAssignTmpArray()
    {
    	$php = <<<'EOT'
<?php
    
namespace Code\ArrayManipulation;
    
class AssignTmpArray
{
    public function test()
    {
        $container = 'test';
        $configurator = $this->inlineArguments($container, $test = array($this->getConfigurator()));
    }
    
    public function getConfigurator()
    {
        return true;
    }
    
    public function inlineArguments($container, $configurator)
    {
        return true;
    }
}
EOT;
    	$zephir = <<<'EOT'
namespace Code\ArrayManipulation;
    
class AssignTmpArray
{
    public function test() -> void
    {
        var container, configurator;
    
        let container = "test";
        let configurator =  this->inlineArguments(container, [this->getConfigurator()]);
    }
    
    public function getConfigurator()
    {
    
        return true;
    }
    
    public function inlineArguments(container, configurator)
    {
    
        return true;
    }
    
}
EOT;
    	$this->assertConvertToZephir($php, $zephir);
    }
}


/*


// zephir

let configurator = this->inlineArguments(container, [definition->getConfigurator()]);

*/