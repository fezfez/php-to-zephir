<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ElsePrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Else";
    }

    /**
     * @param Stmt\Else_ $node
     *
     * @return string
     */
    public function convert(Stmt\Else_ $node)
    {
        return ' else {'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
