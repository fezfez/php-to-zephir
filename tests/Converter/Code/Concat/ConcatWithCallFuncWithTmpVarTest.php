<?php

namespace Converter\Code\Concat;

class ConcatWithCallFuncWithTmpVarTest extends \ConverterBaseTest
{
    public function testConvertingMultipleConcatWithAssign()
    {
        $php = <<<'EOT'
<?php

namespace Code\Concat;

class ConcatAndCallFuncWithTmpVar
{
    public function testArrayDimLeftAssignArrayDimLet()
    {
        $event = 'test'.sprintf('%s', $id = 'test') . sprintf('%s', $test = 'test');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Concat;

class ConcatAndCallFuncWithTmpVar
{
    public function testArrayDimLeftAssignArrayDimLet() -> void
    {
        var event, id, test;
    
        let id = "test";
        let test = "test";
        let event =  "test" . sprintf("%s", id) . sprintf("%s", test);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testConvertingOneConcatWithAssign()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Concat;
    
class ConcatAndCallFuncWithTmpVar
{
    public function testArrayDimLeftAssignArrayDimLet()
    {
        $event = 'test'.sprintf('%s', $id = 'test');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Concat;

class ConcatAndCallFuncWithTmpVar
{
    public function testArrayDimLeftAssignArrayDimLet() -> void
    {
        var event, id;
    
        let id = "test";
        let event =  "test" . sprintf("%s", id);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
