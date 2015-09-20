<?php

namespace Converter\Variable;

class StaticVarTest extends \ConverterBaseTest
{
    public function testAssign()
    {
        $php = <<<'EOT'
<?php

namespace Code\Variable;

class SimpleTmpArray
{
    public function test()
    {
        static $container = 'test';
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Variable;

class SimpleTmpArray
{
    public function test() -> void
    {
        var container;
    
        
            let container = "test";
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testSimple()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Variable;
    
class SimpleTmpArray
{
    public function test()
    {
        static $container;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Variable;

class SimpleTmpArray
{
    public function test() -> void
    {
        var container;
    
    
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
