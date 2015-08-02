<?php

namespace Converter\Code\Method;

class PregMatchTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Method;

class PregMatch
{
    public function simpleTest()
    {
        $regex = '';
        $src = '';
        $matches = '';

        preg_match($regex, $src, $matches);
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class PregMatch
{
    public function simpleTest() -> void
    {
        var regex, src, matches;
    
        let regex = "";
        let src = "";
        let matches = "";
        preg_match(regex, src, matches);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
