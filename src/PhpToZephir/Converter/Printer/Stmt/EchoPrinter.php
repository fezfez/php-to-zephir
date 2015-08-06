<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class EchoPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Echo';
    }

    public function convert(Stmt\Echo_ $node)
    {
        return 'echo '.$this->dispatcher->pCommaSeparated($node->exprs).';';
    }
}
