namespace Code\Condition\IfStmt;

class IfElseCondition
{
    public function test($toto) -> void
    {
        if toto === "tata" {
            echo "tata";
        } elseif toto === "tutu" {
            echo "tutu";
        } else {
            echo "else";
        }
    }

}