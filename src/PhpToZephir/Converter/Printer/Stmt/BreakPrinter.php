<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class BreakPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_Break';
    }

    /**
     * @param Stmt\Break_ $node
     *
     * @return string
     */
    public function convert(Stmt\Break_ $node)
    {
        return 'break'.($node->num !== null ? ' '.$this->dispatcher->p($node->num) : '').';';
    }
}
