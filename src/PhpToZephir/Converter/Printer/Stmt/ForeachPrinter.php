<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ForeachPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Foreach";
    }

    /**
     * @param Stmt\Foreach_ $node
     *
     * @return string
     */
    public function convert(Stmt\Foreach_ $node)
    {
        return 'for '.(null !== $node->keyVar ? $this->dispatcher->p($node->keyVar).', ' : '').$this->dispatcher->p($node->valueVar).
               ' in '.$this->dispatcher->p($node->expr).' {'.
               $this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
