namespace Code\Oop;

class Attribute
{
    public publicAttr = [];
    protected protectedAttr = [];
    protected privateAttr = [];
    public publicStaticAttr = [];
    protected protectedStaticAttr = [];
    protected privateStaticAttr = [];
    const MY_CONST = "";
    public function test() -> void
    {
        var test;
    
        let test =  this->publicAttr;
        let test =  this->protectedAttr;
        let test =  this->privateAttr;
        let test = this->publicStaticAttr;
        let test = this->protectedStaticAttr;
        let test = this->privateStaticAttr;
        let test =  self::MY_CONST;
    }

}