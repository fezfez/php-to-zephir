<?php

namespace Converter\Code\Simple;

class ArrayDimTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php

namespace Code\Simple;

class ArrayDim
{
    private $targets = null;
    private $name = 'toto';
    private $literal = null;
    private $value = 'tutu';
    private $type = 'test';
    private $typeMap = array();
    private $namespaces = null;
    private $imports = array();
    private static $annotationMetadata = array('test');

    public function testArrayDimScalarWithAssignLet()
    {
        $number = 0;
        $myArray = array(1 => 10);

        $myArray[1] = $number++;
    }

    public function testArrayDimLetWithAssignScalar()
    {
        $number = 0;
        $myArray = array(1 => 10);

        $myArray[$number++] = 11;
    }

    public function testArrayDimLeftWithScalarAssignScalar()
    {
        $number = 0;
        $myArray = array(1 => array(2 => 10));

        $myArray[$number++][$number++]['fezfez'][$number++] = $number++;
    }

    public function testAssignLeftWithArrayDimLeftRight()
    {
        $number = 0;
        $myArray = array(1 => array(2 => 10));

        $test = $myArray[$number++][$number++]['fezfez'][$number++];
    }

    public function testArrayDimLeftAssignArrayDimLet()
    {
        $number = 0;
        $myArray = array(1 => 10, 2 => 11);

        $myArray[$number++] = $myArray[$number++];
    }

    private function getConstructor()
    {
        return;
    }

    private function getNumberOfParameters()
    {
        return 5;
    }

    public function arrayDimAssignObjectPropertie()
    {
        $metadata = array();
        $test = true;

        $metadata['tutu'] = $test;
        $metadata['targets'] = $this->targets;
        $metadata['properties'][$this->name] = $this->name;

        $metadata['enum'][$this->name]['literal'] = (! empty($this->literal))
        ? $this->literal
        : $this->value;

        $type = isset($this->typeMap[$this->type])
        ? $this->typeMap[$this->type]
        : $this->type;

        $name = "test";

        $alias = (false === $pos = strpos($name, '\\')) ? $name : substr($name, 0, $pos);

        if ($this->namespaces) {
            echo 'toto';
        } elseif (isset($this->imports[$loweredAlias = strtolower($alias)])) {
            echo 'im converted !';
        }

        if (! $property = self::$annotationMetadata[$name]['default_property']) {
        }

        $lineCnt = 0;
        $lineNumber = 1;
        if ($lineCnt++ == $lineNumber) {
        }
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
    protected targets = null;
    protected name = "toto";
    protected literal = null;
    protected value = "tutu";
    protected type = "test";
    protected typeMap = [];
    protected namespaces = null;
    protected imports = [];
    protected static annotationMetadata = ["test"];
    public function testArrayDimScalarWithAssignLet() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : 10];
        let number++;
        let myArray[1] = number;
    }
    
    public function testArrayDimLetWithAssignScalar() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : 10];
        var tmpArray;
        let number++;
        let myArray[number] = 11;
    }
    
    public function testArrayDimLeftWithScalarAssignScalar() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : [2 : 10]];
        var tmpArray;
        let number++;
        let number++;
        let tmpArray = myArray["fezfez"];
        let number++;
        let number++;
        let myArray[number] = number;
    }
    
    public function testAssignLeftWithArrayDimLeftRight() -> void
    {
        var number, myArray, test;
    
        let number = 0;
        
        let myArray =  [1 : [2 : 10]];
        var tmpArray;
        let number++;
        let number++;
        let tmpArray = myArray["fezfez"];
        let number++;
        let test = myArray[number];
    }
    
    public function testArrayDimLeftAssignArrayDimLet() -> void
    {
        var number, myArray;
    
        let number = 0;
        
        let myArray =  [1 : 10, 2 : 11];
        var tmpArray;
        let number++;
        var tmpArray;
        let number++;
        let myArray[number] = myArray[number];
    }
    
    protected function getConstructor()
    {
        
        return;
    }
    
    protected function getNumberOfParameters()
    {
        
        return 5;
    }
    
    public function arrayDimAssignObjectPropertie() -> void
    {
        var metadata, test, type, name, alias, pos, loweredAlias, property, lineCnt, lineNumber;
    
        
        let metadata =  [];
        let test =  true;
        let metadata["tutu"] = test;
        let metadata["targets"] = this->targets;
        let metadata["properties"][this->name] = this->name;
        
        let metadata["enum"][this->name]["literal"] =  !empty(this->literal) ? this->literal : this->value;
        
        let type =  isset this->typeMap[this->type] ? this->typeMap[this->type] : this->type;
        let name = "test";
        let pos =  strpos(name, "\\");
        let alias =  pos === false ? name : substr(name, 0, pos);
        
        if this->namespaces {
            echo "toto";
        } else {
        let loweredAlias =  strtolower(alias);
        if isset this->imports[loweredAlias] {
            echo "im converted !";
        }
        }
        let property = self::annotationMetadata[name]["default_property"];
        if !property {
            echo "not allowed";
        }
        let lineCnt = 0;
        let lineNumber = 1;
        let lineCnt++;
        if lineCnt == lineNumber {
            echo "not allowed";
        }
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
