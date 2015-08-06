<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class ConcatPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_AssignOp_Concat';
    }

    public function convert(AssignOp\Concat $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Concat', $node->var, ' .= ', $node->expr);
    }
}
