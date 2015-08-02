<?php

namespace Converter\Code\TryCatch;

class SimpleTryCatchTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\TryCatch;

class SimpleTryCatch
{
    public function test()
    {
        try {
            echo 'try';
        } catch (Exception $e) {
            echo 'catsh';
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\TryCatch;

class SimpleTryCatch
{
    public function test() -> void
    {
        var e;
    
        try {
            echo "try";
        } catch Exception, e {
            echo "catsh";
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
