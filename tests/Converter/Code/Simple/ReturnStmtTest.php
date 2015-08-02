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
        var tmpArray961e689622b008ac464bf70d9d437c4d;
    
        let tmpArray961e689622b008ac464bf70d9d437c4d = ["foo" : "bar"];
        return tmpArray961e689622b008ac464bf70d9d437c4d;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
