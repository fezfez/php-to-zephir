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
        var container, configurator, tmpArray8184aefe7d1180b34c53e15ef5a12249;
    
        let container = "test";
        let tmpArray8184aefe7d1180b34c53e15ef5a12249 = [this->getConfigurator()];
        let configurator =  this->inlineArguments(container, tmpArray8184aefe7d1180b34c53e15ef5a12249);
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
        var container, configurator, test;
    
        let container = "test";
        
        let test =  [this->getConfigurator()];
        let configurator =  this->inlineArguments(container, test);
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
