<?php

namespace Converter\Code\Oop;

class InstanceTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Oop;

class Instance
{
    public function test()
    {
        $myInstance = new StdClass();
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Oop;

class Instance
{
    public function test() -> void
    {
        var myInstance;
    
        let myInstance =  new StdClass();
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
