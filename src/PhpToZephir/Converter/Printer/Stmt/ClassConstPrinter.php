<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ClassConstPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_ClassConst";
    }

    public function convert(Stmt\ClassConst $node)
    {
        return 'const '.$this->dispatcher->pCommaSeparated($node->consts).';';
    }
}
