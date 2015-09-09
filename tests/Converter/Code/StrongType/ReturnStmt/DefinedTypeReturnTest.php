<?php

namespace Converter\Code\StrongType\ReturnStmt;

class DefinedTypeReturnTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return string
     */
    public function test($toto)
    {
        return $toto;
    }
    
    /**
     * @return string return a super string
     */
    public function test2($toto)
    {
        return $toto;
    }
}
EOT;

        $zephir = <<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return string
     */
    public function test(toto) -> string
    {
        return toto;
    }
    
    /**
     * @return string return a super string
     */
    public function test2(toto) -> string
    {
        return toto;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
