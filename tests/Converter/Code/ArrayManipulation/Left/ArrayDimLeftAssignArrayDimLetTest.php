<?php

namespace Converter\Code\ArrayManipulation\Left;

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
        var number, myArray, tmpNumber1, tmpNumber2;
    
        let number = 0;
        let myArray =  [1 : 10, 2 : 11];
        
        let number++;
        let tmpNumber1 = number;
        
        
        let number++;
        let tmpNumber2 = number;
        
        let myArray[tmpNumber1] = myArray[tmpNumber2];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
