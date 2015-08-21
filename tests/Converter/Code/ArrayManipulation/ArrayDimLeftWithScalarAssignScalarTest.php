<?php

namespace Converter\Code\ArrayManipulation;

class ArrayDimLeftWithScalarAssignScalarTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimLeftWithScalarAssignScalar()
    {
        $number = 0;
        $myArray = array(1 => array(2 => 10));

        $myArray[$number++][$number++]['fezfez'][$number++] = $number++;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimLeftWithScalarAssignScalar() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : [2 : 10]];
        var tmpArray;
        let number++;
        let number++;
        let tmpArray = myArray["fezfez"];
        let number++;
        let number++;
        let myArray[number] = number;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
