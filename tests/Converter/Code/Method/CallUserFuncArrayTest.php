<?php

namespace Converter\Code\Method;

class CallUserFuncArrayTest extends \ConverterBaseTest
{
    
    public function testOnArrayAccess()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\Method;
    
class CallUserFuncArray
{
    protected $dispatcher;

    public function simpleTest()
    {
        $method = 'test';
        $arguments = array();
        return call_user_func_array(array($this->dispatcher, $method), $arguments);
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Method;

class CallUserFuncArray
{
    protected dispatcher;
    public function simpleTest()
    {
        var method, arguments, tmpArray679d025e144e01e72d3d2a6e800187cd;
    
        let method = "test";
        
        let arguments =  [];
        let tmpArray679d025e144e01e72d3d2a6e800187cd = [this->dispatcher, method];
        return call_user_func_array(tmpArray679d025e144e01e72d3d2a6e800187cd, arguments);
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}