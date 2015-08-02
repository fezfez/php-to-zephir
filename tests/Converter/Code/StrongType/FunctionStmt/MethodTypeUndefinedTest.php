<?php

namespace Converter\Code\StrongType\FunctionStmt;

class MethodTypeUndefinedTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\StrongType\FunctionStmt;

class MethodTypeUndefined
{
    public function test($toto)
    {
        $test = 'tutu';
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\StrongType\FunctionStmt;

class MethodTypeUndefined
{
    public function test(toto) -> void
    {
        var test;
    
        let test = "tutu";
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
