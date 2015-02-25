<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ForPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_For";
    }

    /**
     * @param Stmt\For_ $node
     *
     * @return string
     */
    public function convert(Stmt\For_ $node)
    {
        return 'for '
             .$this->dispatcher->pCommaSeparated($node->init).';'.(!empty($node->cond) ? ' ' : '')
             .$this->dispatcher->pCommaSeparated($node->cond).';'.(!empty($node->loop) ? ' ' : '')
             .$this->dispatcher->pCommaSeparated($node->loop)
             .' {'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
