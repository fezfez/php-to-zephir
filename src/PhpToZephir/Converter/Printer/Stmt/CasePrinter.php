<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class CasePrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_Case';
    }

    /**
     * @param Stmt\Case_ $node
     *
     * @return string
     */
    public function convert(Stmt\Case_ $node)
    {
        return (null !== $node->cond ? 'case '.$this->dispatcher->p($node->cond) : 'default').':'
             .$this->dispatcher->pStmts($node->stmts);
    }
}
