<?php

namespace Converter\Code\ArrayManipulation;

class ArrayDimLetWithAssignScalarTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
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

class ArrayDim
{
    public function testArrayDimLetWithAssignScalar() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : 10];
        var tmpArray;
        let number++;
        let myArray[number] = 11;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
