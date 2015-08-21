<?php

namespace Converter\Code\ArrayManipulation;

class ArrayDimLeftAssignArrayDimLetTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimLeftAssignArrayDimLet()
    {
        $number = 0;
        $myArray = array(1 => 10, 2 => 11);

        $myArray[$number++] = $myArray[$number++];
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimLeftAssignArrayDimLet() -> void
    {
        var number, myArray, tmpVar1;
    
        let number = 0;
        
        let myArray =  [1 : 10, 2 : 11];
        let number++;
        let tmpVar1 = number;
        let number++;
        let myArray[tmpVar1] = myArray[number];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
