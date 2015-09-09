<?php

namespace Converter\Code\Condition\IfStmt;

class IfWithVarModificationInLeftTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt;

class IfWithVarModificationInLeft
{
    public function test()
    {
        $lineCnt = 0;
        $lineNumber = 1;
        if ($lineCnt++ == $lineNumber) {
        }
    }

}
EOT;

        $zephirExpected = <<<EOT
namespace Code\Condition\IfStmt;

class IfWithVarModificationInLeft
{
    public function test() -> void
    {
        var lineCnt, lineNumber;
    
        let lineCnt = 0;
        let lineNumber = 1;
        let lineCnt++;
        if lineCnt == lineNumber {
            echo "not allowed";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephirExpected);
    }
}
