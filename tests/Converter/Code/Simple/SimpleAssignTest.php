<?php

namespace Converter\Code\Simple;

class SimpleAssignTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class SimpleAssign
{
    public function test()
    {
        $myString = 'foo';
        $myString .= 'bar';

        $myString &= 'test';

        $myNumber = 1;
        $myNumber += 2;
        $myNumber -= 1;
        $myNumber *= 2;
        $myNumber /= 2;
        $myNumber %= 2;
        $myNumber++;
        ++$myNumber;
        $myNumber--;
        --$myNumber;

        $result = 1 + $myNumber;
        $result = 1 * $myNumber;
        $result = 1 / $myNumber;
        $result = 1 % $myNumber;

        $superResult = $result.$myNumber;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class SimpleAssign
{
    public function test() -> void
    {
        var myString, myNumber, result, superResult;
    
        let myString = "foo";
        let myString .= "bar";
        let myString = "test";
        let myNumber = 1;
        let myNumber += 2;
        let myNumber -= 1;
        let myNumber *= 2;
        let myNumber /= 2;
        let myNumber %= 2;
        let myNumber++;
        let myNumber++;
        let myNumber--;
        let myNumber--;
        let result =  1 + myNumber;
        let result =  1 * myNumber;
        let result =  1 / myNumber;
        let result =  1 % myNumber;
        let superResult =  result . myNumber;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
