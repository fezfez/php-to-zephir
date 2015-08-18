<?php

namespace Converter\Code\Loops\ForStmt;

class ForEqualsTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForEquals
{
    public function testSampleFromPhpDoc1()
    {
	    for ($i = 1; $i <= 10; $i++) {
		    echo $i;
		}
	}
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForEquals
{
    public function testSampleFromPhpDoc1() -> void
    {
        var i;
    
        let i = 1;
        for i in range(1, 10) {
            echo i;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
