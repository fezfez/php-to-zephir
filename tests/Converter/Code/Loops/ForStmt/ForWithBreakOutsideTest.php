<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithBreakOutsideTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForWithBreakOutside
{
    public function testSampleFromPhpDoc2()
    {
        for ($i = 1; ; $i++) {
            if ($i > 10) {
                break;
            }
            echo $i;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForWithBreakOutside
{
    public function testSampleFromPhpDoc2() -> void
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
