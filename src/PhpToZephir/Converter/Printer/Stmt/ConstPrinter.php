<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ConstPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Const';
    }

    public function convert(Stmt\Const_ $node)
    {
        return 'const '.$this->dispatcher->pCommaSeparated($node->consts).';';
    }
}
