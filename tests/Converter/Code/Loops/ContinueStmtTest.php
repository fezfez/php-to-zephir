<?php

namespace Converter\Code\Loops\ForStmt;

class ContinueStmtTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops;

class ContinueStmt
{
    public function test()
    {
        $tests = array('im a test');

        foreach ($tests as $test) {
            continue;
        }

        foreach ($tests as $test) {
            continue 1;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops;

class ContinueStmt
{
    public function test() -> void
    {
        var tests, test;
    
        
        let tests =  ["im a test"];
        for test in tests {
            continue;
        }
        for test in tests {
            continue;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
