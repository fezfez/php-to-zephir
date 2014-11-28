<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class MulPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Mul";
    }

    public function convert(BinaryOp\Mul $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Mul', $node->left, ' * ', $node->right);
    }
}
