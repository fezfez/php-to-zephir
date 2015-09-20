<?php

namespace Converter\Code\ArrayManipulation;

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
        var number, myArray, tmpnumber;
    
        let number = 0;
        let myArray =  [1 : 10];
        
        let number++;
        let tmpnumber = number;
        
        let myArray[tmpnumber] = 11;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
