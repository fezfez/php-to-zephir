namespace Code\Simple;

class Cast
{
    public function test() -> void
    {
        var maValue;
    
        let maValue = "1";
        let maValue =  (int) maValue;
        let maValue =  (double) maValue;
        let maValue =  (string) maValue;
        let maValue =  (array) maValue;
        let maValue =  (object) maValue;
        let maValue =  (bool) maValue;
    }

}