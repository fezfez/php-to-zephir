<?php

namespace Converter\Code\Condition\IfStmt;

class InstanceOfTest extends \ConverterBaseTest
{
    public function testConvertingNegative()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt;

class InstanceOfTest
{
    public function test($toto)
    {
        $listener = '';
        if (!$listener instanceof WrappedListener) {
            echo 'tata';
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Condition\IfStmt;

class InstanceOfTest
{
    public function test(toto) -> void
    {
        var listener;
    
        let listener = "";
        if !(listener instanceof WrappedListener) {
            echo "tata";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testConvertingPositive()
    {
        $php = <<<'EOT'
<?php

namespace Code\Condition\IfStmt;

class InstanceOfTest
{
    public function test($toto)
    {
        $listener = '';
        if ($listener instanceof WrappedListener) {
            echo 'tata';
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Condition\IfStmt;

class InstanceOfTest
{
    public function test(toto) -> void
    {
        var listener;
    
        let listener = "";
        if listener instanceof WrappedListener {
            echo "tata";
        }
    }

}
EOT;
    	$this->assertConvertToZephir($php, $zephir);
    }
}
