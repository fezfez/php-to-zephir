namespace Code\Simple;

class SimpleAssign
{
    public function test() -> void
    {
        var myString, myNumber, result, superResult;
    
        let myString = "foo";
        let myString .= "bar";
        let myString = "test";
        let myNumber = 1;
        let myNumber += 2;
        let myNumber -= 1;
        let myNumber *= 2;
        let myNumber /= 2;
        let myNumber %= 2;
        let myNumber++;
        let myNumber++;
        let myNumber--;
        let myNumber--;
        let result =  1 + myNumber;
        let result =  1 * myNumber;
        let result =  1 / myNumber;
        let result =  1 % myNumber;
        let superResult =  result . myNumber;
    }

}