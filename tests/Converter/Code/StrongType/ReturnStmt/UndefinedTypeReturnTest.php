<?php

namespace Converter\Code\StrongType\ReturnStmt;

class UndefinedTypeReturnTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class UndefinedTypeReturn
{
    public function test($toto)
    {
        return $toto;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\StrongType\ReturnStmt;

class UndefinedTypeReturn
{
    public function test(toto)
    {
        
        return toto;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
