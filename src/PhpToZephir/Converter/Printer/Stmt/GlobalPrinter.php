<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class GlobalPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Global';
    }

    public function convert(Stmt\Global_ $node)
    {
        return 'global '.$this->dispatcher->pCommaSeparated($node->vars).';';
    }
}
