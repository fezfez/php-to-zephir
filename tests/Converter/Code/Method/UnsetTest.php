<?php

namespace Converter\Code\Method;

class UnsetTest extends \ConverterBaseTest
{
    public function testOnVar()
    {
        $php = <<<'EOT'
<?php

namespace Code\Method;

class TestUnset
{
    public function simpleTest()
    {
        $foo = 'simpleTest';

        unset($foo);
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class TestUnset
{
    public function simpleTest() -> void
    {
        var foo;
    
        let foo = "simpleTest";
        let foo = null;
    
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testOnArrayAccess()
    {
    	$php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class TestUnset
{
    public function simpleTest()
    {
        $foo = array('simpleTest');
    
        unset($foo['simpleTest']);
    }
}
EOT;
    	$zephir = <<<'EOT'
namespace Code\Method;

class TestUnset
{
    public function simpleTest() -> void
    {
        var foo;
    
        let foo =  ["simpleTest"];
        unset foo["simpleTest"];
    
    }

}
EOT;
    	$this->assertConvertToZephir($php, $zephir);
    }
    
    public function testOnPropertyAccess()
    {
    	$php = <<<'EOT'
<?php
    
namespace Code\Method;

class TestUnset
{
    public $foo;
    public function simpleTest()
    {
        unset($this->foo);
    }
}
EOT;
    	$zephir = <<<'EOT'
namespace Code\Method;

class TestUnset
{
    public foo;
    public function simpleTest() -> void
    {
        unset this->foo;
    
    }

}
EOT;
    	$this->assertConvertToZephir($php, $zephir);
    }
}
