namespace Code\Condition\IfStmt;

class IfWithCreateTmpVarInCondition
{
    public function test(toto) -> void
    {
        var tmpArray40cd750bba9870f18aada2478b24840a, tmpArray6ef611fccd6ae1abb1ff53cf46464309;
    
        let tmpArray40cd750bba9870f18aada2478b24840a = [];
        if toto == tmpArray40cd750bba9870f18aada2478b24840a {
            echo "tata";
        }
        
        if toto == "im a string" {
            echo "tata";
        }
        
        if toto == false {
            echo "tata";
        }
        let tmpArray6ef611fccd6ae1abb1ff53cf46464309 = [10];
        if toto === true && toto === tmpArray6ef611fccd6ae1abb1ff53cf46464309 {
            echo "tata";
        }
        let tmpArray40cd750bba9870f18aada2478b24840a = [];
        if toto === true && toto === tmpArray40cd750bba9870f18aada2478b24840a || toto === false {
            echo "tata";
        }
        let tmpArray40cd750bba9870f18aada2478b24840a = [];
        if toto === true && toto === tmpArray40cd750bba9870f18aada2478b24840a || toto === false && toto === true {
            echo "tata";
        }
    }

}