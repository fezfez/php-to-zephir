<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseAndPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_BitwiseAnd";
    }

    public function convert(BinaryOp\BitwiseAnd $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_BitwiseAnd', $node->left, ' & ', $node->right);
    }
}
