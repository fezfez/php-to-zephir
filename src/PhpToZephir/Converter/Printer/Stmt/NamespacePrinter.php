<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class NamespacePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Namespace";
    }

    public function convert(Stmt\Namespace_ $node)
    {
        return 'namespace '.implode('\\', $node->name->parts).';'."\n".$this->dispatcher->pStmts($node->stmts, false);
    }
}
