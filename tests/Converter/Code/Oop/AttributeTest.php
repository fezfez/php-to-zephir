<?php

namespace Converter\Code\Oop;

class AttributeTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
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
        $zephir = <<<'EOT'
namespace Code\Oop;

class Attribute
{
    public publicAttr = [];
    protected protectedAttr = [];
    protected privateAttr = [];
    public static publicStaticAttr = [];
    protected static protectedStaticAttr = [];
    protected static privateStaticAttr = [];
    const MY_CONST = "";
    public function test() -> void
    {
        var test;
    
        let test =  this->publicAttr;
        let test =  this->protectedAttr;
        let test =  this->privateAttr;
        let test =  self::publicStaticAttr;
        let test =  self::protectedStaticAttr;
        let test =  self::privateStaticAttr;
        let test =  self::MY_CONST;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
