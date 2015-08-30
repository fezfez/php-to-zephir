<?php

namespace Converter\Code\Oop;

class UnkownInterfaceTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Oop;

class MyClass implements UnkownInterface
{

}
EOT;

        $this->assertConvertToZephir($php, null);
    }
}
