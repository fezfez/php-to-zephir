<?php

namespace Converter\Code\StrongType\ReturnStmt;

class DefinedInInterfaceReturnTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = array(
            <<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

interface MyInterface
{
    /**
     * @return string
     */
	public function test($toto);
}
EOT
,
<<<'EOT'
<?php

namespace Code\StrongType\ReturnStmt;

class DefinedInInterfaceReturn implements MyInterface
{
    public function test($toto)
    {
        return $toto;
    }
}
EOT
);
        $zephir = array(<<<'EOT'
namespace Code\StrongType\ReturnStmt;

interface MyInterface
{
    /**
     * @return string
     */
    public function test(toto) -> string;

}
EOT
,
<<<'EOT'
namespace Code\StrongType\ReturnStmt;

class DefinedInInterfaceReturn implements MyInterface
{
    public function test(toto) -> string
    {
        
        return toto;
    }

}
EOT
);
        $this->assertConvertToZephir($php, $zephir);
    }
}
