<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class ShiftRightPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_ShiftRight";
    }

    public function convert(BinaryOp\ShiftRight $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_ShiftRight', $node->left, ' >> ', $node->right);
    }
}
