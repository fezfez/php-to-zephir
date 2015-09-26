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
    
    public function testInInterface()
    {
        $this->markTestIncomplete("Support in version 0.3");

    	$php = array(<<<'EOT'
<?php
    
namespace Code\StrongType\FunctionStmt;
    
class MethodTypeWithScalarType implements MethodTypeWithScalarTypeInterface
{
    public function test($toto, $titi, $tata, $tutu, $foo, $bar)
    {
    }
}
EOT
,
    <<<'EOT'
<?php

namespace Code\StrongType\FunctionStmt;

interface MethodTypeWithScalarTypeInterface
{
    /**
     * @param string  $toto
     * @param boolean $titi
     * @param float   $tata
     * @param array   $tutu
     * @param double  $foo
     */
    public function test($toto, $titi, $tata, $tutu, $foo, $bar);
}
EOT
);
    			$zephir = array(<<<'EOT'
namespace Code\StrongType\FunctionStmt;

class MethodTypeWithScalarType implements MethodTypeWithScalarTypeInterface
{
    public function test(string toto, boolean titi, float tata, array tutu, double foo, bar) -> void
    {
    }

}
EOT
,
<<<'EOT'
namespace Code\StrongType\FunctionStmt;

interface MethodTypeWithScalarTypeInterface
{
    /**
     * @param string  $toto
     * @param boolean $titi
     * @param float   $tata
     * @param array   $tutu
     * @param double  $foo
     */
    public function test(string toto, boolean titi, float tata, array tutu, double foo, bar) -> void;
}
EOT
);
    	$this->assertConvertToZephir($php, $zephir);
    }
}
