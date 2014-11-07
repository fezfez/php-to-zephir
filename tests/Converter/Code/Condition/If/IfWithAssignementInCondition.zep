class Sample
{
    public function test($toto) -> void
    {
        var averylongvariable;
    
        let averylongvariable = toto;
        
        if averylongvariable {
            echo "tata";
        }
    }
    
    public function testWithConditionAndAssign($toto) -> void
    {
        var averylongvariable, tata;
    
        let tata = toto;
        let averylongvariable = toto;
    
        if toto === true && averylongvariable && tata {
            echo "tata";
        }
    }

}