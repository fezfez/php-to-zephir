<?php

namespace Converter\Code\StrongType\ReturnStmt;

class DefinedTypeReturnTest extends \ConverterBaseTest
{
    public function testConvertingScalar()
    {
        $php = <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return string
     */
    public function test($toto)
    {
        return $toto;
    }
    
    /**
     * @return string return a super string
     */
    public function test2($toto)
    {
        return $toto;
    }
}
EOT;

        $zephir = <<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return string
     */
    public function test(toto) -> string
    {
        return toto;
    }
    
    /**
     * @return string return a super string
     */
    public function test2(toto) -> string
    {
        return toto;
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testConvertingWithNativeClass()
    {
        $php = <<<'EOT'
<?php
    
namespace Code\StrongType\ReturnStmt;
    
class DefinedTypeReturn
{
    /**
     * @return \Exception
     */
    public function test($toto)
    {
        return new \Exception("test");
    }
    
    /**
     * @return \Exception return a super string
     */
    public function test2($toto)
    {
        return new \Exception("test");
    }
}
EOT;
    
        $zephir = <<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return \Exception
     */
    public function test(toto) -> <\Exception>
    {
        return new \Exception("test");
    }
    
    /**
     * @return \Exception return a super string
     */
    public function test2(toto) -> <\Exception>
    {
        return new \Exception("test");
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testConvertingClassInNamespace()
    {
        $php = array(<<<'EOT'
<?php
    
namespace Code\StrongType\ReturnStmt;
    
class DefinedTypeReturn
{
    /**
     * @return MyClass
     */
    public function test($toto)
    {
        return new MyClass();
    }
    
    /**
     * @return MyClass return a super class
     */
    public function test2($toto)
    {
        return new MyClass();
    }
}
EOT
,
<<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class MyClass
{
}
EOT
);
    
        $zephir = array(<<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return MyClass
     */
    public function test(toto) -> <MyClass>
    {
        return new MyClass();
    }
    
    /**
     * @return MyClass return a super class
     */
    public function test2(toto) -> <MyClass>
    {
        return new MyClass();
    }

}
EOT
,
<<<'EOT'
namespace Code\StrongType\ReturnStmt;

class MyClass
{
}
EOT
);
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testConvertingClassInNamespace2()
    {
        $php = array(<<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return Up\MyClass
     */
    public function test($toto)
    {
        return new Up\MyClass();
    }
    
    /**
     * @return Up\MyClass return a super class
     */
    public function test2($toto)
    {
        return new Up\MyClass();
    }
}
EOT
            ,
            <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt\Up;

class MyClass
{
}
EOT
        );
    
        $zephir = array(<<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return Up\MyClass
     */
    public function test(toto) -> <Up\MyClass>
    {
        return new Up\MyClass();
    }
    
    /**
     * @return Up\MyClass return a super class
     */
    public function test2(toto) -> <Up\MyClass>
    {
        return new Up\MyClass();
    }

}
EOT
            ,
            <<<'EOT'
namespace Code\StrongType\ReturnStmt\Up;

class MyClass
{
}
EOT
        );
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testConvertingClassWithUse()
    {
        $php = array(<<<'EOT'
<?php
    
namespace Code\StrongType\ReturnStmt;

use Code\StrongType\ReturnStmt\Up\MyClass;

class DefinedTypeReturn
{
    /**
     * @return MyClass
     */
    public function test($toto)
    {
        return new MyClass();
    }
    
    /**
     * @return MyClass return a super class
     */
    public function test2($toto)
    {
        return new MyClass();
    }
}
EOT
            ,
            <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt\Up;

class MyClass
{

}
EOT
        );
    
        $zephir = array(<<<'EOT'
namespace Code\StrongType\ReturnStmt;

use Code\StrongType\ReturnStmt\Up\MyClass;
class DefinedTypeReturn
{
    /**
     * @return MyClass
     */
    public function test(toto) -> <MyClass>
    {
        return new MyClass();
    }
    
    /**
     * @return MyClass return a super class
     */
    public function test2(toto) -> <MyClass>
    {
        return new MyClass();
    }

}
EOT
            ,
            <<<'EOT'
namespace Code\StrongType\ReturnStmt\Up;

class MyClass
{
}
EOT
        );
        $this->assertConvertToZephir($php, $zephir);
    }
    
    public function testConvertingClassFullQualified()
    {
        $php = array(<<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return \Code\StrongType\ReturnStmt\MyClass
     */
    public function test($toto)
    {
        return new MyClass();
    }
    
    /**
     * @return \Code\StrongType\ReturnStmt\MyClass return a super class
     */
    public function test2($toto)
    {
        return new MyClass();
    }
}
EOT
            ,
            <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class MyClass
{
}
EOT
        );
    
        $zephir = array(<<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedTypeReturn
{
    /**
     * @return \Code\StrongType\ReturnStmt\MyClass
     */
    public function test(toto) -> <\Code\StrongType\ReturnStmt\MyClass>
    {
        return new MyClass();
    }
    
    /**
     * @return \Code\StrongType\ReturnStmt\MyClass return a super class
     */
    public function test2(toto) -> <\Code\StrongType\ReturnStmt\MyClass>
    {
        return new MyClass();
    }

}
EOT
            ,
            <<<'EOT'
namespace Code\StrongType\ReturnStmt;

class MyClass
{
}
EOT
        );
        $this->assertConvertToZephir($php, $zephir);
    }
}
