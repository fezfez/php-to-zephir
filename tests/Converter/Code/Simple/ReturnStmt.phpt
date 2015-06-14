--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
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
echo $engine->convertString($code, true);

?>
--EXPECT--
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