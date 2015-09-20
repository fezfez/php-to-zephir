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
        var number, myArray, tmpnumber;
    
        let number = 0;
        let myArray =  [1 : 10, 2 : 11];
        
        let number++;
        let tmpnumber = number;
        let number++;
        
        let myArray[tmpnumber] = myArray[number];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
