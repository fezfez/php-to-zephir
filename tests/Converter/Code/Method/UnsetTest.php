<?php

namespace Converter\Code\Method;

class UnsetTest extends \ConverterBaseTest
{
    public function testConverting()
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
        unset foo;
    
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
