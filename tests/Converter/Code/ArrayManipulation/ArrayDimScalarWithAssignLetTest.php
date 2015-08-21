<?php

namespace Converter\Code\ArrayManipulation;

class ArrayDimScalarWithAssignLetTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimScalarWithAssignLet()
    {
        $number = 0;
        $myArray = array(1 => 10);

        $myArray[1] = $number++;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDim
{
    public function testArrayDimScalarWithAssignLet() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : 10];
        let number++;
        let myArray[1] = number;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
