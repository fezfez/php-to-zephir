<?php

namespace Converter\Code\ArrayManipulation;

class ArrayPlusTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\ArrayManipulation;

class ArrayPlus
{
    public function test()
    {
        $info = array("test");
        $info += array("type" => "Closure", "pretty" => "closure");
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class ArrayPlus
{
    public function test() -> void
    {
        var info;
    
        
        let info =  ["test"];
        let info = this->array_plus(info, ["type" : "Closure", "pretty" : "closure"]);
    }

    private function array_plus(array1, array2)
    {
        var union, key, value;
        let union = array1;
        for key, value in array2 {
            if false === array_key_exists(key, union) {
                let union[key] = value;
            }
        }
        
        return union;
    }
}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
