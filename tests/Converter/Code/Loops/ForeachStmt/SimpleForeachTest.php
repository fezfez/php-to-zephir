<?php

namespace Converter\Code\Loops\ForeachStmt;

class SimpleForeachTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForeachStmt;

class SimpleForeach
{
    public function test()
    {
        $myArray = array('test', '2');

        foreach ($myArray as $myValue) {
            echo "my stmt";
        }

        foreach ($myArray as $myKey => $myValue) {
            echo "my stmt";
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForeachStmt;

class SimpleForeach
{
    public function test() -> void
    {
        var myArray, myValue, myKey;
    
        let myArray =  ["test", "2"];
        for myValue in myArray {
            echo "my stmt";
        }
        for myKey, myValue in myArray {
            echo "my stmt";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
