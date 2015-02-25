namespace Code\TryCatch;

class SimpleTryCatch
{
    public function test() -> void
    {
        var e;
    
        try {
            echo "try";
        } catch Exception, e {
            echo "catsh";
        }
    }

}