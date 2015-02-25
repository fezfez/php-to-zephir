namespace Code\Condition\IfStmt;

class IfWithAssignementArrayDimInCondition
{
    public function test() -> void
    {
        var toto, averylongvariable;
    
        
        let toto =  [1 : true];
        let averylongvariable = toto[1];
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testIncrementInArrayDim() -> void
    {
        var i, toto, averylongvariable;
    
        let i = 0;
        
        let toto =  [1 : true];
        // @FIXME the i++ is extract twice
        let i++;;
        var tmpArray;
        let i++;
        let averylongvariable = toto[i];
        if averylongvariable {
            echo "tata";
        }
    }

}