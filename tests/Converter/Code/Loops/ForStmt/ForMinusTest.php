<?php

namespace Converter\Code\Loops\ForStmt;

class ForMinusTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForMinus
{
    public function testSimple()
    {
		for ($i = 0; $i < 10; $i++) {
			echo $i;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForMinus
{
    public function testSimple() -> void
    {
        var i;
    
        let i = 0;
        for i in range(0, 9) {
            echo i;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
