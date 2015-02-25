namespace Code\Condition\SwitchStmt;

class SwitchWithCondition
{
    public function test(toto) -> void
    {
        
        if is_array(toto) {
            echo "array";
        } else { 
        
        if is_dir(toto) || is_file(toto) || is_executable(toto) {
            echo "filesysteme";
        }
         else { 
        
        if is_bool(toto) === true {
            echo "bool";
        }
         else {
            echo "what do you mean ?";
        }}}
    }
    
    public function testWithFirstWithoutStmt(toto) -> void
    {
        
        if is_array(toto) || is_bool(toto) === true || is_dir(toto) || is_file(toto) || is_executable(toto) {
            echo "filesysteme";
        } else {
            echo "what do you mean ?";
        }
    }

}