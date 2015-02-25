<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ThrowPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Throw";
    }

    /**
     * @param Stmt\Throw_ $node
     *
     * @return string
     */
    public function convert(Stmt\Throw_ $node)
    {
        return 'throw '.$this->dispatcher->p($node->expr).';';
    }
}
