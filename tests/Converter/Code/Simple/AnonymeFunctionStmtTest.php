<?php

namespace Converter\Code\Simple;

class AnonymeFunctionStmtTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Simple;

class AnonymeFunctionStmt
{
    public function test($test)
    {
        return function ($tutu) use ($test) {
            echo $tutu.$test;
        };
    }

    public function testIt()
    {
        $anonyme = $this->test('bar');

        $anonyme("foor");
    }
}

EOT;
        $zephir = array(
<<<'EOT'
namespace Code\Simple;

class AnonymeFunctionStmt
{
    public function test(test)
    {
        
        return new AnonymeFunctionStmttestClosureOne(test);
    }
    
    public function testIt() -> void
    {
        var anonyme;
    
        let anonyme =  this->test("bar");
        {anonyme}("foor");
    }

}
EOT
,
<<<'EOT'
namespace Code\Simple;

class AnonymeFunctionStmttestClosureZero
{
    private test;

    public function __construct(test)
    {
                let this->test = test;

    }

    public function __invoke(tutu)
    {
    echo tutu . this->test;
    }
}
EOT
);
        $this->assertConvertToZephir($php, $zephir);
    }
}

