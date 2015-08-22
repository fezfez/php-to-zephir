<?php

namespace Converter\Code\Simple;

class SilentErrorTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class SilentError
{
    public function test()
    {
        @unlink('test');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class SilentError
{
    public function test() -> void
    {
        unlink("test");
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
