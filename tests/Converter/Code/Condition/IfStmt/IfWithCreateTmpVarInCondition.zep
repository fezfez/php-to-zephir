namespace Code\Condition\IfStmt;

class IfWithCreateTmpVarInCondition
{
    public function test($toto) -> void
    {
        var tmp;
        let tmp = [];
        if tmp == toto {
            echo "tata";
        }
        if toto == "im a string" {
            echo "tata";
        }
        if toto == false {
            echo "tata";
        }
        if toto === true && toto === tmp {
            echo "tata";
        }
        if toto === true && toto === tmp || toto === false {
            echo "tata";
        }
        if toto === true && toto === tmp || toto === false && toto === true {
            echo "tata";
        }
    }

}