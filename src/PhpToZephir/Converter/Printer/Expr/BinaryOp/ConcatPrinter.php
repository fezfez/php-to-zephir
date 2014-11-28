<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class ConcatPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Concat";
    }

    public function convert(BinaryOp\Concat $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Concat', $node->left, ' . ', $node->right);
    }
}
