<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class DeclarePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Declare";
    }

    public function convert(Stmt\Declare_ $node)
    {
        return 'declare ('.$this->dispatcher->pCommaSeparated($node->declares).') {'
             .$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
