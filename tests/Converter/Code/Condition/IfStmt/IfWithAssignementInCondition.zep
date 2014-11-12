namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
    public function test($toto) -> void
    {
        var averylongvariable;
    
        let averylongvariable = toto;
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testWithConditionAndAssign($toto, $twoAssignedVariable, $treeAssignedVariable) -> void
    {
        var twoAssignVariable, treeAssignVariable;
    
        let twoAssignVariable = twoAssignedVariable;
        let treeAssignVariable = treeAssignedVariable;
        if toto === true && (twoAssignVariable && treeAssignVariable) {
            echo "tata";
        }
    }

}