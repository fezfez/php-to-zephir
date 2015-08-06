<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class SmallerPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_Smaller';
    }

    public function convert(BinaryOp\Smaller $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Smaller', $node->left, ' < ', $node->right);
    }
}
