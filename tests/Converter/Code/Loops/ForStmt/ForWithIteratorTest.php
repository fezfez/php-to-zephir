<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithIteratorTest extends \ConverterBaseTest
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
        i = 0;
        $tokens = array();
        for (reset($tokens); false !== $token = current($tokens); next($tokens)) {
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
