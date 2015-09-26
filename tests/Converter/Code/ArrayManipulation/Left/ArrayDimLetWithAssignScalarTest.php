<?php

namespace Converter\Code\ArrayManipulation\Left;

class ArrayDimLetWithAssignScalarTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDimLetWithAssignScalarTest
{
    public function testArrayDimLetWithAssignScalar()
    {
        $number = 0;
        $myArray = array(1 => 10);

        $myArray[$number++] = 11;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDimLetWithAssignScalarTest
{
    public function testArrayDimLetWithAssignScalar() -> void
    {
        var number, myArray, tmpNumber1;
    
        let number = 0;
        let myArray =  [1 : 10];
        
        let number++;
        let tmpNumber1 = number;
        
        let myArray[tmpNumber1] = 11;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
