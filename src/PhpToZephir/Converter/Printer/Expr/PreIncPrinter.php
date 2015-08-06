<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PreIncPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_PreInc';
    }

    /**
     * @param Expr\PreInc $node
     *
     * @return string
     */
    public function convert(Expr\PreInc $node)
    {
        return 'let '.$this->dispatcher->pPostfixOp('Expr_PostInc', $node->var, '++');
    }
}
