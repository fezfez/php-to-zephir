<?php

namespace Code\Simple;

class ReturnStmt
{
    public function testReturnWithAssign()
    {
        // return $this->classExists[$fqcn] = AnnotationRegistry::loadAnnotationClass($fqcn);
        return $test = 'fez';
    }

    public function testReturnArray()
    {
        return ["foo" => "bar"];
    }
}