<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class StaticPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Static';
    }

    public function convert(Stmt\Static_ $node)
    {
        return 'static '.$this->dispatcher->pCommaSeparated($node->vars).';';
    }
}
