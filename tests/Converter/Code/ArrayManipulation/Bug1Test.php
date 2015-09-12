<?php

namespace Converter\Code\ArrayManipulation;

class Bug1Test extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\ArrayManipulation;

class Bug1
{
    const SCOPE_CONTAINER = '';

    private $scopes = array();
    private $scopedServices = array();

    public function test()
    {
        $name = 'test';

        if (!isset($this->scopedServices[$this->scopes[$name]])) {
            throw new Exception(sprintf('The parent scope "%s" must be active when entering this scope.', $this->scopes[$name]));
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\ArrayManipulation;

class Bug1
{
    const SCOPE_CONTAINER = "";
    protected scopes = [];
    protected scopedServices = [];
    public function test() -> void
    {
        var name;
    
        let name = "test";
        if !(isset this->scopedServices[this->scopes[name]]) {
            throw new Exception(sprintf("The parent scope \"%s\" must be active when entering this scope.", this->scopes[name]));
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
