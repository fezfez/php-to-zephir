<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class MinusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Minus";
    }

    public function convert(BinaryOp\Minus $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Minus', $node->left, ' - ', $node->right);
    }
}
