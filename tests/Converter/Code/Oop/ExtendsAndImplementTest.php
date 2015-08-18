<?php

namespace Converter\Code\Oop;

class ExtendsAndImplementTest extends \ConverterBaseTest
{
    public function testWithUse()
    {
        $php = array(<<<'EOT'
<?php

namespace Code\Oop;

use Code\Myclass;
use Code\MyImplementation;

class ExtendsAndImplementTest extends MyClass implements MyImplementation
{

}
EOT
,
<<<'EOT'
<?php

namespace Code;

class MyClass
{

}
EOT
,
<<<'EOT'
<?php

namespace Code;

interface MyImplementation
{

}
EOT
);
        $zephir = array(<<<'EOT'
namespace Code\Oop;

use Code\Myclass;
use Code\MyImplementation;
class ExtendsAndImplementTest extends MyClass implements MyImplementation
{
}
EOT
,
<<<'EOT'
namespace Code;

class MyClass
{
}
EOT
,
<<<'EOT'
namespace Code;

interface MyImplementation
{
}
EOT
);    
        $this->assertConvertToZephir($php, $zephir);
    }

    public function testWithoutUse()
    {
        $php = array(<<<'EOT'
<?php
    
namespace Code\Oop;

class ExtendsAndImplementTest extends MyClass implements MyImplementation
{
    
}
EOT
,
<<<'EOT'
<?php
    
namespace Code\Oop;
    
class MyClass
{
    
}
EOT
,
<<<'EOT'
<?php
    
namespace Code\Oop;
    
interface MyImplementation
{
    
}
EOT
);

        $zephir = array(<<<'EOT'
namespace Code\Oop;

class ExtendsAndImplementTest extends MyClass implements MyImplementation
{
}
EOT
,
<<<'EOT'
namespace Code\Oop;

class MyClass
{
}
EOT
,
<<<'EOT'
namespace Code\Oop;

interface MyImplementation
{
}
EOT
        );
        $this->assertConvertToZephir($php, $zephir);
    }
}
