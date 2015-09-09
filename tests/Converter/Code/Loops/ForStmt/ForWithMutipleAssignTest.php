<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithMutipleAssignTest extends \ConverterBaseTest
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

        for ($i = 0, $count = count($myArray); $i < $count; $i++) {
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
        var myArray, i, count;
    
        let myArray =  ["test", "2"];
        let i = 0;
        let count =  count(myArray);
        for i in range(0, count) {
            echo i;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
