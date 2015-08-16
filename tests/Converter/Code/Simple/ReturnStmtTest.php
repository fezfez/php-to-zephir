<?php

namespace Converter\Code\Simple;

class ReturnStmtTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ReturnStmt
{
    public function testReturnWithAssign()
    {
        // return $this->classExists[$fqcn] = AnnotationRegistry::loadAnnotationClass($fqcn);
        return $test = 'fez';
    }

    public function testReturnArray()
    {
        return ["foo" => "bar"];
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Simple;

class ReturnStmt
{
    public function testReturnWithAssign()
    {
        var test;
    
        // return $this->classExists[$fqcn] = AnnotationRegistry::loadAnnotationClass($fqcn);
        let test = "fez";
        return test;
    }
    
    public function testReturnArray()
    {
        var tmpArrayf6499d168b352149fb71f58a79c076bd;
    
        let tmpArrayf6499d168b352149fb71f58a79c076bd = ["foo" : "bar"];
        return tmpArrayf6499d168b352149fb71f58a79c076bd;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
