<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class PowPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Pow";
    }

    public function convert(BinaryOp\Pow $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Pow', $node->left, ' ** ', $node->right);
    }
}
