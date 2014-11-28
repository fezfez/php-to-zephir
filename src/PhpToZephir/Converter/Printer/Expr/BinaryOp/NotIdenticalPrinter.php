<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class NotIdenticalPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_NotIdentical";
    }

    public function convert(BinaryOp\NotIdentical $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_NotIdentical', $node->left, ' !== ', $node->right);
    }
}
