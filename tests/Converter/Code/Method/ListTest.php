<?php

namespace Converter\Code\Method;

class ListTest extends \ConverterBaseTest
{
    public function testOnVar()
    {
        $php = <<<'EOT'
<?php

namespace Code\Method;

class TestList
{
    public function simpleTest()
    {
        list($test, $test2) = $this->returnArray();
    }

    private function returnArray()
    {
        return array('test', 'test2');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class TestList
{
    public function simpleTest() -> void
    {
        var test, test2, tmpListTestTest2;
    
        let tmpListTestTest2 = this->returnArray();
        let test = tmpListTestTest2[0];
        let test2 = tmpListTestTest2[1];
    }
    
    protected function returnArray()
    {
        var tmpArraye76de54ae3d204be562cca3d399ae3f0;
    
        let tmpArraye76de54ae3d204be562cca3d399ae3f0 = ["test", "test2"];
        return tmpArraye76de54ae3d204be562cca3d399ae3f0;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testOnArray()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class TestList
{
    public function simpleTest()
    {
        $test = array();
        list($test['tmp1'], $test['tmp2']) = $this->returnArray();
    }
    
    private function returnArray()
    {
        return array('test', 'test2');
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class TestList
{
    public function simpleTest() -> void
    {
        var test, tmpListTesttmp1Testtmp2;
    
        let test =  [];
        let tmpListTesttmp1Testtmp2 = this->returnArray();
        let test["tmp1"] = tmpListTesttmp1Testtmp2[0];
        let test["tmp2"] = tmpListTesttmp1Testtmp2[1];
    }
    
    protected function returnArray()
    {
        var tmpArraybcff7c15eb96f987cada3530993887e4;
    
        let tmpArraybcff7c15eb96f987cada3530993887e4 = ["test", "test2"];
        return tmpArraybcff7c15eb96f987cada3530993887e4;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
