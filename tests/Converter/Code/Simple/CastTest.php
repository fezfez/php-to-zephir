<?php

namespace Converter\Code\Simple;

class CastTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Simple;

class Cast
{
    public function test()
    {
        $maValue = '1';

        $maValue = (int) $maValue;
        $maValue = (double) $maValue;
        $maValue = (string) $maValue;
        $maValue = (array) $maValue;
        $maValue = (object) $maValue;
        $maValue = (bool) $maValue;
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class Cast
{
    public function test() -> void
    {
        var maValue;
    
        let maValue = "1";
        let maValue =  (int) maValue;
        let maValue =  (double) maValue;
        let maValue =  (string) maValue;
        let maValue =  (array) maValue;
        let maValue =  (object) maValue;
        let maValue =  (bool) maValue;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
