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

namespace Code\Oop;

class Attribute
{
    public $publicAttr = array();
    protected $protectedAttr = array();
    private $privateAttr = array();

    public static $publicStaticAttr = array();
    protected static $protectedStaticAttr = array();
    private static $privateStaticAttr = array();

    const MY_CONST = array();

    public function test()
    {
    	$test = $this->publicAttr;
    	$test = $this->protectedAttr;
    	$test = $this->privateAttr;

    	$test = self::publicStaticAttr;
    	$test = self::protectedStaticAttr;
    	$test = self::privateStaticAttr;

    	$test = self::MY_CONST;
    }
}
EOT;
echo $engine->convertString($code, true);

?>
--EXPECT--
namespace Code\Oop;

class Attribute
{
    public publicAttr = [];
    protected protectedAttr = [];
    protected privateAttr = [];
    public publicStaticAttr = [];
    protected protectedStaticAttr = [];
    protected privateStaticAttr = [];
    const MY_CONST = "";
    public function test() -> void
    {
        var test;
    
        let test =  this->publicAttr;
        let test =  this->protectedAttr;
        let test =  this->privateAttr;
        let test = this->publicStaticAttr;
        let test = this->protectedStaticAttr;
        let test = this->privateStaticAttr;
        let test =  self::MY_CONST;
    }

}