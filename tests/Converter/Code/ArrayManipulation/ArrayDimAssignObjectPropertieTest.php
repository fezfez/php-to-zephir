<?php

namespace Converter\Code\ArrayManipulation;

class ArrayDimAssignObjectPropertieTest extends \ConverterBaseTest
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
    public function arrayDimAssignObjectPropertie() -> void
    {
        var metadata, test, type, name, alias, pos, loweredAlias, property, lineCnt, lineNumber;
    
        
        let metadata =  [];
        let test =  true;
        let metadata["tutu"] = test;
        let metadata["targets"] = this->targets;
        let metadata["properties"][this->name] = this->name;
        
        let metadata["enum"][this->name]["literal"] =  !empty(this->literal) ? this->literal  : this->value;
        
        let type =  isset this->typeMap[this->type] ? this->typeMap[this->type]  : this->type;
        let name = "test";
        let pos =  strpos(name, "\\");
        let alias =  pos === false ? name  : substr(name, 0, pos);
        
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

}
EOT;
        $this->assertConvertToZephir($php, $zephir, true);
    }
}
