<?php

namespace Converter\Code\StrongType\FunctionStmt;

class MethodTypeWithObjectTypeTest extends \ConverterBaseTest
{
    public function testClass()
    {
        $php = array(<<<'EOT'
<?php

namespace Code\StrongType\FunctionStmt;

use Code\StrongType\MyParametterTwo;

class MyClass
{
    public function test(MyParametter $parametter, MyParametterTwo $parametterTwo)
    {
    }
}
EOT
,
        <<<'EOT'
<?php

namespace Code\StrongType\FunctionStmt;

class MyParametter
{
}
EOT
,
<<<'EOT'
<?php

namespace Code\StrongType;

class MyParametterTwo
{
}
EOT
);
        $zephir = array(<<<'EOT'
namespace Code\StrongType\FunctionStmt;

use Code\StrongType\MyParametterTwo;
class MyClass
{
    public function test(<MyParametter> parametter, <MyParametterTwo> parametterTwo) -> void
    {
    }

}
EOT
,
        <<<'EOT'
namespace Code\StrongType\FunctionStmt;

class MyParametter
{
}
EOT
,
<<<'EOT'
namespace Code\StrongType;

class MyParametterTwo
{
}
EOT
);
        $this->assertConvertToZephir($php, $zephir);
    }
}
