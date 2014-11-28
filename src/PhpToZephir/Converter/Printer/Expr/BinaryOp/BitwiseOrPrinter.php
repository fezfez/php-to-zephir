<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseOrPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_BitwiseOr";
    }

    public function convert(BinaryOp\BitwiseOr $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_BitwiseOr', $node->left, ' | ', $node->right);
    }
}
