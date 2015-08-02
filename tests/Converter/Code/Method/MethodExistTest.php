<?php

namespace Converter\Code\Method;

class MethodExistTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Method;

class MethodExist
{
    public function simpleTest()
    {
        $foo = 'simpleTest';

        method_exists(self, $foo);
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class MethodExist
{
    public function simpleTest() -> void
    {
        var foo;
    
        let foo = "simpleTest";
        method_exists(self, foo);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
