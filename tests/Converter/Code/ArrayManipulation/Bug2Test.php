<?php

namespace Converter\Code\ArrayManipulation;

class Bug2Test extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\ArrayManipulation;

class Bug2
{
    private $parameters = array();

    public function test()
    {
        $type = 'test';
        $parameter = array('name' => 'test');

        $this->parameters['$' . $parameter['name']] = array($type, '');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class Bug2
{
    protected parameters = [];
    public function test() -> void
    {
        var type, parameter;
    
        let type = "test";
        let parameter =  ["name" : "test"];
        let this->parameters["$" . parameter["name"]] =  [type, ""];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
