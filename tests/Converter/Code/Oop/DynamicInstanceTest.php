<?php

namespace Converter\Code\Oop;

class DynamicInstanceTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Oop;

class DynamicInstance
{
    public function test()
    {
        $myClass = 'test';
        
        $myInstance = new $myClass();
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Oop;

class DynamicInstance
{
    public function test() -> void
    {
        var myClass, myInstance;
    
        let myClass = "test";
        let myInstance =  new {myClass}();
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
