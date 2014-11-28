<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class GreaterOrEqualPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_GreaterOrEqual";
    }

    public function convert(BinaryOp\GreaterOrEqual $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_GreaterOrEqual', $node->left, ' >= ', $node->right);
    }
}
