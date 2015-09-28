<?php

namespace Converter\Code\Loops\WhileStmt;

class ListInWhileTest extends \ConverterBaseTest
{
    public function testConverting()
    {
        $php = <<<'EOT'
<?php
namespace Code\Loops\WhileStmt;

class ListInWhileTest
{
    public function whilethewhilewhile()
    {
        $idMap = array(array('test', 'test2'));
        $params = array();
        $columnNames = array();
        while (list($fieldName, $value) = each($idMap)) {
            $params[] = $value;
            $columnNames[] = $fieldName;
        }
    }
}
EOT;
        $zephir = <<<'EOT'
namespace Code\Loops\WhileStmt;

class ListInWhileTest
{
    public function whilethewhilewhile() -> void
    {
        var idMap, params, columnNames, fieldName, value, tmpListFieldNameValue;
    
        let idMap =  [["test", "test2"]];
        let params =  [];
        let columnNames =  [];
        let tmpListFieldNameValue = each(idMap);
        let fieldName = tmpListFieldNameValue[0];
        let value = tmpListFieldNameValue[1];
        while (tmpListFieldNameValue) {
            let params[] = value;
            let columnNames[] = fieldName;
        let tmpListFieldNameValue = each(idMap);
        let fieldName = tmpListFieldNameValue[0];
        let value = tmpListFieldNameValue[1];
        }
    }

}
EOT;
        $this->assertConvertToZephir($php, $zephir);
    }
}
