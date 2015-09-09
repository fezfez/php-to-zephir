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
        var number, myArray, test;
    
        let number = 0;
        let myArray =  [1 : [2 : 10]];
        var tmpArray;
        let number++;
        let number++;
        let tmpArray = myArray["fezfez"];
        let number++;
        let test = myArray[number];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
