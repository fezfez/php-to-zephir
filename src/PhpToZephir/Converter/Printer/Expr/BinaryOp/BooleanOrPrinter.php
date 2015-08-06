<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class BooleanOrPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_BooleanOr';
    }

    public function convert(BinaryOp\BooleanOr $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_BooleanOr', $node->left, ' || ', $node->right);
    }
}
