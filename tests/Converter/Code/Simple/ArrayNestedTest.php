<?php

namespace Converter\Code\Simple;

class ArrayNestedTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayNested
{
    public function test()
    {
    	$return = array(array("a"=>"apple"), array("b" => "ball"));
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayNested
{
    public function test() -> void
    {
        var returnn;
    
        
        let returnn =  [["a" : "apple"], ["b" : "ball"]];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
