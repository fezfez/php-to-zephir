<?php

namespace Converter\Code\Condition;

class TernaryOperatorTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition;

class TernaryOperator
{
	public function test()
    {
        $tmp = 'Hello '.(null ?: 'Guest');
    }
}
EOT;

        $zephirExpected = <<<EOT
namespace Code\Condition;

class TernaryOperator
{
    public function test() -> void
    {
        var tmp;
    
        let tmp =  "Hello ".  ( null ? null : "Guest");
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephirExpected);
    }
}
