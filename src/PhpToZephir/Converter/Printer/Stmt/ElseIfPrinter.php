<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ElseIfPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_ElseIf';
    }

    /**
     * @param Stmt\ElseIf_ $node
     *
     * @return string
     */
    public function convert(Stmt\ElseIf_ $node)
    {
        return ' elseif '.$this->dispatcher->p($node->cond).' {'
             .$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
