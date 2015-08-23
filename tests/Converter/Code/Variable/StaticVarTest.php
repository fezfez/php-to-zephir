<?php

namespace Converter\Variable;

class StaticVarTest extends \ConverterBaseTest
{
    public function testSimpleTmpArray()
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
}
