--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
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
echo $engine->convertString($code, true);

?>
--EXPECT--
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