<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class LogicalAndPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_LogicalAnd";
    }

    public function convert(BinaryOp\LogicalAnd $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_LogicalAnd', $node->left, ' and ', $node->right);
    }
}
