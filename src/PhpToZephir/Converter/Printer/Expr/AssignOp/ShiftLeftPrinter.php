<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class ShiftLeftPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_ShiftLeft";
    }

    public function convert(AssignOp\ShiftLeft $node)
    {
        return $this->dispatcher->pInfixOp('Expr_AssignOp_ShiftLeft', $node->var, ' <<= ', $node->expr);
    }
}
