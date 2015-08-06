<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class LogicalOrPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_LogicalOr';
    }

    public function convert(BinaryOp\LogicalOr $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_LogicalOr', $node->left, ' or ', $node->right);
    }
}
