<?php

namespace Converter\Code\ArrayManipulation\Both;

class AssignLeftWithArrayDimLeftRightTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    public function testAssignLeftWithArrayDimLeftRight()
    {
        $number = 0;
        $myArray = array(1 => array(2 => 10));

        $test = $myArray[$number++][$number++]['fezfez'][$number++];
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDim
{
    public function testAssignLeftWithArrayDimLeftRight() -> void
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
        
        let test = myArray[tmpNumber1][tmpNumber2]["fezfez"][tmpNumber3];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
