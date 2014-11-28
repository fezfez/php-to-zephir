<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class ShiftRightPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_ShiftRight";
    }

    public function convert(AssignOp\ShiftRight $node)
    {
        return $this->dispatcher->pInfixOp('Expr_AssignOp_ShiftRight', $node->var, ' >>= ', $node->expr);
    }
}
