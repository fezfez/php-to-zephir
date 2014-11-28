<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpToZephir\Converter\SimplePrinter;

class BooleanAndPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_BooleanAnd";
    }

    public function convert(BinaryOp\BooleanAnd $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_BooleanAnd', $node->left, ' && ', $node->right);
    }
}
