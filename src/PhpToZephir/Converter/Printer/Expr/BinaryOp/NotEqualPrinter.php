<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class NotEqualPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_NotEqual';
    }

    public function convert(BinaryOp\NotEqual $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_NotEqual', $node->left, ' != ', $node->right);
    }
}
