namespace Code\Simple;

class ReturnStmt
{
    public function testReturnWithAssign()
    {
        var test;
    
        // return $this->classExists[$fqcn] = AnnotationRegistry::loadAnnotationClass($fqcn);
        let test = "fez";
        return test;
    }
    
    public function testReturnArray()
    {
        let tmpArray961e689622b008ac464bf70d9d437c4d = ["foo" : "bar"];
        return tmpArray961e689622b008ac464bf70d9d437c4d;
    }

}