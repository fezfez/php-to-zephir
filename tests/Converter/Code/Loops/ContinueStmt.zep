namespace Code\Loops;

class ContinueStmt
{
    public function test() -> void
    {
        var tests, test;
    
        
        let tests =  ["im a test"];
        for test in tests {
            continue;
        }
        for test in tests {
            continue;
        }
    }

}