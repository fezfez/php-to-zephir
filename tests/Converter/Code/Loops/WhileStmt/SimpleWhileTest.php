<?php

namespace Converter\Code\Loops\WhileStmt;

class SimpleWhileTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\WhileStmt;

class SimpleWhile
{
    public function test()
    {
        while (true) {
            break;
        }
    }

    public function whileWithAssign()
    {
        $pos = 0;
        $input = 'mySuperString';

        while (($pos = strpos($input, '@', $pos)) !== false) {
			echo $pos;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\WhileStmt;

class SimpleWhile
{
    public function test() -> void
    {
        while (true) {
            break;
        }
    }
    
    public function whileWithAssign() -> void
    {
        var pos, input;
    
        let pos = 0;
        let input = "mySuperString";
        let pos =  strpos(input, "@", pos);
        while (pos !== false) {
            echo pos;
        let pos =  strpos(input, "@", pos);
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
