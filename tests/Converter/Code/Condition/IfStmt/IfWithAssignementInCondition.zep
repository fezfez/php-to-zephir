namespace Code\Condition\IfStmt;

class IfWithAssignementInCondition
{
    protected ignoredAnnotationNames = [];
    public function test(toto) -> void
    {
        var averylongvariable;
    
        let averylongvariable = toto;
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testWithConditionAndAssign(toto, twoAssignedVariable, treeAssignedVariable) -> void
    {
        var twoAssignVariable, treeAssignVariable;
    
        let twoAssignVariable = twoAssignedVariable;;
        let treeAssignVariable = treeAssignedVariable;
        if toto === true && twoAssignVariable {
            echo "tata";
        }
    }
    
    protected function getName()
    {
        
        return "myName";
    }
    
    public function testWithArrayDimAssign()
    {
        var name;
    
        let name =  this->getName();
        if isset this->ignoredAnnotationNames[name] {
            
            return this->ignoredAnnotationNames[name];
        }
    }

}