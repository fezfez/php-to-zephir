<?php

namespace Converter\Code\Condition\IfStmt;

class IsWithYodaConditionTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt;

class IsWithYodaCondition
{
    public function test($toto)
    {
        if ('tata' === $toto) {
            echo 'tata';
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Condition\IfStmt;

class IsWithYodaCondition
{
    public function test(toto) -> void
    {
        
        if toto === "tata" {
            echo "tata";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
