namespace Code\Method;

class MethodExist
{
    public function simpleTest() -> void
    {
        var foo;
    
        let foo = "simpleTest";
        method_exists(self, foo);
    }

}