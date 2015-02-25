namespace Code\Simple;

class StringConcat
{
    public function testConcatAndReturnConcat()
    {
        var foo, works;
    
        let foo = "foo";
        let works =  "bar" . foo . "bar";
        
        return foo . "bar" . "bar";
    }
    
    public function testConcatAndReturn()
    {
        
        return "bar {foo} bar";
    }

}