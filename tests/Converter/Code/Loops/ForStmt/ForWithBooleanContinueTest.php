<?php

namespace Converter\Code\Loops\ForStmt;

class ForWithBooleanContinueTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\ForStmt;

class ForWithBooleanContinue
{
    public function test()
    {
        $messages = array();

        for ($i = 0; isset($messages[$i]); $i++) {
            $messages[$i] = $messages[$i];
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\ForStmt;

class ForWithBooleanContinue
{
    public function test() -> void
    {
        var messages, i;
    
        let messages =  [];
        
            let i = 0;
        loop {
        if isset messages[i] {
            break;
        }
        
            let messages[i] = messages[i];
        
            let i++;
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
