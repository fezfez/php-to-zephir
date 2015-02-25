namespace Code\Loops\ForeachStmt;

class SimpleForeach
{
    public function test() -> void
    {
        var myArray, myValue, myKey;
    
        
        let myArray =  ["test", "2"];
        for myValue in myArray {
        }
        for myKey, myValue in myArray {
        }
    }

}