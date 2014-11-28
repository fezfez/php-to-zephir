<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class GreaterPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Greater";
    }

    public function convert(BinaryOp\Greater $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Greater', $node->left, ' > ', $node->right);
    }
}
