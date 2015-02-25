<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class CatchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Catch";
    }

    /**
     * @param Stmt\Catch_ $node
     *
     * @return string
     */
    public function convert(Stmt\Catch_ $node)
    {
        return ' catch '.$this->dispatcher->p($node->type).', '.$node->var.' {'
             .$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
