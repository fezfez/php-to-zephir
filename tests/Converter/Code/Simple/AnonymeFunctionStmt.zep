namespace Code\Simple;

class AnonymeFunctionStmt
{
    public function test(test)
    {
        
        return new AnonymeFunctionStmttestClosureOne(test);
    }
    
    public function testIt() -> void
    {
        var anonyme;
    
        let anonyme =  this->test("bar");
        {anonyme}("foor");
    }

}