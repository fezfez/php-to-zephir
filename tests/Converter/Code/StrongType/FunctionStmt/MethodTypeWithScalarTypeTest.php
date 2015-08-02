<?php

namespace Converter\Code\StrongType\FunctionStmt;

class MethodTypeWithScalarTypeTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\StrongType\FunctionStmt;

class MethodTypeWithScalarType
{
    /**
     * @param string  $toto
     * @param boolean $titi
     * @param float   $tata
     * @param array   $tutu
     * @param double  $foo
     */
    public function test($toto, $titi, $tata, $tutu, $foo, $bar)
    {
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\StrongType\FunctionStmt;

class MethodTypeWithScalarType
{
    /**
     * @param string  $toto
     * @param boolean $titi
     * @param float   $tata
     * @param array   $tutu
     * @param double  $foo
     */
    public function test(string toto, boolean titi, float tata, array tutu, double foo, bar) -> void
    {
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
