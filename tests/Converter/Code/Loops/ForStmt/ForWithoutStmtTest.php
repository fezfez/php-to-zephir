<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithoutStmtTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForWithoutStmt
{
    public function testSampleFromPhpDoc3()
    {
		$i = 1;
		for (; ; ) {
		    if ($i > 10) {
		        break;
		    }
		    echo $i;
		    $i++;
		}
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForWithoutStmt
{
    public function testSampleFromPhpDoc3() -> void
    {
        var i;
    
        let i = 1;
        loop {
        
            if i > 10 {
                break;
            }
            echo i;
            let i++;
        
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
