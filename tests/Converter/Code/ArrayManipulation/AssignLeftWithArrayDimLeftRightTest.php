<?php

namespace Converter\Code\ArrayManipulation;

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
        var number, myArray, test, tmpNumber1, tmpNumber2;
    
        let number = 0;
        let myArray =  [1 : [2 : 10]];

        let number++;
        let tmpNumber1 = number;
        let number++;
        let tmpNumber2 = number;
        let number++;

        let test = myArray[tmpNumber1][tmpNumber2]["fezfez"][number];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
