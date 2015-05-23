--TEST--
Test continue stmt
--DESCRIPTION--
Lowlevel basic test
--FILE--
<?php

require __DIR__ . '/../../../../Bootstrap.php';

use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use Symfony\Component\Console\Output\NullOutput;

$engine = EngineFactory::getInstance(new Logger(new NullOutput(), false));
$code   = <<<'EOT'
<?php

namespace Code\Condition\SwitchStmt;

class SwitchWithCondition
{
    public function test($toto)
    {
        switch (true) {
            case is_array($toto):
                echo 'array';
                break;
            case is_bool($toto) === true:
                echo 'bool';
                break;
            case is_dir($toto):
            case is_file($toto):
            case is_executable($toto):
                echo 'filesysteme';
                break;
            default:
                echo 'what do you mean ?';
                break;
        }
    }

    public function testWithFirstWithoutStmt($toto)
    {
        switch (true) {
            case is_array($toto):
            case is_bool($toto) === true:
            case is_dir($toto):
            case is_file($toto):
            case is_executable($toto):
                echo 'filesysteme';
                break;
            default:
                echo 'what do you mean ?';
                break;
        }
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Condition\SwitchStmt;

class SwitchWithCondition
{
    public function test(toto) -> void
    {
        
        if is_array(toto) {
            echo "array";
        } else { 
        
        if is_dir(toto) || is_file(toto) || is_executable(toto) {
            echo "filesysteme";
        }
         else { 
        
        if is_bool(toto) === true {
            echo "bool";
        }
         else {
            echo "what do you mean ?";
        }}}
    }
    
    public function testWithFirstWithoutStmt(toto) -> void
    {
        
        if is_array(toto) || is_bool(toto) === true || is_dir(toto) || is_file(toto) || is_executable(toto) {
            echo "filesysteme";
        } else {
            echo "what do you mean ?";
        }
    }

}