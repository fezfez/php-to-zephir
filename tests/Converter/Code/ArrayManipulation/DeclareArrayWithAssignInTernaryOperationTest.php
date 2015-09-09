<?php

namespace Converter\Code\ArrayManipulation;

class DeclareArrayWithAssignInTernaryOperationTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    private function getConstructor()
    {
        return;
    }

    private function getNumberOfParameters()
    {
        return 5;
    }

    public function declareArrayWithAssignInTernaryOperation()
    {
        $metadata = array(
            'default_property' => null,
            'has_constructor'  => (null !== $constructor = $this->getConstructor()) && $this->getNumberOfParameters() > 0,
        );
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ArrayDim
{
    protected function getConstructor()
    {
        return;
    }
    
    protected function getNumberOfParameters()
    {
        return 5;
    }
    
    public function declareArrayWithAssignInTernaryOperation() -> void
    {
        var metadata, constructor;
    
        let constructor =  this->getConstructor();
        let metadata =  ["default_property" : null, "has_constructor" : constructor !== null && this->getNumberOfParameters() > 0];
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
