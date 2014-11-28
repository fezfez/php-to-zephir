<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class IdenticalPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Identical";
    }

    public function convert(BinaryOp\Identical $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Identical', $node->left, ' === ', $node->right);
    }
}
