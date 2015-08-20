<?php

namespace Converter\ArrayManipulation;

class FuncCallInArrayTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\ArrayManipulation;

class FuncCallInArray
{
    public function test()
    {
        $name = 'Test';
        $style = 'test';
        $styles = array();
        $styles[strtolower($name)] = $style;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class FuncCallInArray
{
    public function test() -> void
    {
        var name, style, styles;
    
        let name = "Test";
        let style = "test";
        
        let styles =  [];
        let styles[strtolower(name)] = style;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
