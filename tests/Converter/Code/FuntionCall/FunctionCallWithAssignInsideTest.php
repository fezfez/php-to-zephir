<?php

namespace Converter\FunctionCall;

class FunctionCallWithAssignInsideTest extends \ConverterBaseTest
{
    public function testAssign()
    {
        $php = <<<'EOT'
<?php

namespace Code\FunctionCall;

class FunctionCallWithAssignInsideTest
{
    public function test()
    {
        $name = array('toto');
        $test2 = strtolower($test = implode(',', $name));
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\FunctionCall;

class FunctionCallWithAssignInsideTest
{
    public function test() -> void
    {
        var name, test2, test;
    
        let name =  ["toto"];
        let test =  implode(",", name);
        let test2 =  strtolower(test);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
