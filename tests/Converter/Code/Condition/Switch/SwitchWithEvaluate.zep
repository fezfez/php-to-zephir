class Sample
{
    public function test($toto) -> void
    {
        switch (toto) {
            case "{":
                echo "array";
                break;
            case "]":
                echo "bool";
                break;
            case "|":
            case "-":
            case "5":
                echo "filesysteme";
                break;
            default:
                echo "what do you mean ?";
                break;
        }
    }

}