<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class SmallerOrEqualPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_SmallerOrEqual";
    }

    public function convert(BinaryOp\SmallerOrEqual $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_SmallerOrEqual', $node->left, ' <= ', $node->right);
    }
}
