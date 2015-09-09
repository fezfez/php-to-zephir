<?php

namespace Converter\Code\Simple;

class StringConcatTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        $foo = "foo";
        $works = "bar".$foo."bar";

        return "bar".$foo."bar";
    }

    public function testConcatAndReturn()
    {
        return "bar $foo bar";
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        var foo, works;
    
        let foo = "foo";
        let works =  "bar" . foo . "bar";
        return "bar" . foo . "bar";
    }
    
    public function testConcatAndReturn()
    {
        return "bar {foo} bar";
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
