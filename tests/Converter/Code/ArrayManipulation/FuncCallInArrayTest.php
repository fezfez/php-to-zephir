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

    public function testOnBothSideSide()
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
        $styles2 = array("test");
        $styles[strtolower($name)] = $styles2[strtolower($name)];
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class FuncCallInArray
{
    public function test() -> void
    {
        var name, style, styles, styles2;
    
        let name = "Test";
        let style = "test";
        let styles =  [];
        let styles2 =  ["test"];
        let styles[strtolower(name)] = styles2[strtolower(name)];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testWithAssignInside()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\ArrayManipulation;
    
class FuncCallInArray
{
    public function test()
    {
        $name = array('AAA', 'AA');
        $styles = array();
        $styles[$test2 = strtolower($test = implode(',', $name))] = "hi!";
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class FuncCallInArray
{
    public function test() -> void
    {
        var name, style, styles, styles2;
    
        let name =  ["AAA", "AA"];
        let styles =  [];
        
        let test = implode(',', $name);
        let test2 =  strtolower(test);
        
        let styles[test2] = "hi!";
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testWithAssignInsideInBoth()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\ArrayManipulation;
    
class FuncCallInArray
{
    public function test()
    {
        $name = array('AAA', 'AA');
        $styles = array();
        $style2 = array('aaa,aa');
        $styles[$test2 = strtolower($test = implode(',', $name))] = $styles2[$test2 = strtolower($test = implode(',', $name))];
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;
    
class FuncCallInArray
{
    public function test() -> void
    {
        var name, style, styles, styles2;
    
        let name =  ["AAA", "AA"];
        let styles =  [];
    
        let test = implode(',', $name);
        let test2 =  strtolower(test);
    
        let styles[test2] = "hi!";
    }
    
}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
