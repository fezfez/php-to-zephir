<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class ShiftLeftPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_ShiftLeft";
    }

    public function convert(BinaryOp\ShiftLeft $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_ShiftLeft', $node->left, ' << ', $node->right);
    }
}
