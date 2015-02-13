namespace Code\Condition\IfStmt;

class IfElseCondition
{
    protected staticArray = [];
    public function test(toto)
    {
        var toReturn;
    
        let toReturn =  null;
        
        if toto === "tata" {
            let toReturn = "tata";
        } else { 
        
        if toto === "tutu" {
            let toReturn = "tutu";
        }
         else {
            let toReturn = "else";
        }}
        
        return toReturn;
    }
    
    public static function imStatic()
    {
        
        return null;
    }
    
    public function testFuncCallIncondition() -> void
    {
        
        if this->test("tata") {
            echo "tutu";
        }
        
        if self::imStatic() {
            echo "static funcall!";
        }
        
        if isset this->staticArray["test"] {
            echo "static array !";
        }
    }

}