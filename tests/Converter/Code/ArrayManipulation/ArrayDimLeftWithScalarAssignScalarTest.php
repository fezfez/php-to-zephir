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
        var number, myArray, test, tmpNumber1, tmpNumber2, tmpNumber3;
    
        let number = 0;
        let myArray =  [1 : [2 : 10]];

        let number++;
        let tmpNumber1 = number;
        let number++;
        let tmpNumber2 = number;
        let number++;
        let tmpNumber3 = number;
        let number++;

        let myArray[tmpNumber1][tmpNumber2]["fezfez"][tmpNumber3] = number;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
