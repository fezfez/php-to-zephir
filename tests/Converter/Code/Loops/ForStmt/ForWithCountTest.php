<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithCountTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForWithCount
{
    public function testWithCall()
    {
        $myArray = array('test', '2');

        for ($i = 0; $i < count($myArray); $i++) {
			echo $i;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForWithCount
{
    public function testWithCall() -> void
    {
        var myArray, i;
    
        
        let myArray =  ["test", "2"];
        for i in range(0, count(myArray)) {
            echo i;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
