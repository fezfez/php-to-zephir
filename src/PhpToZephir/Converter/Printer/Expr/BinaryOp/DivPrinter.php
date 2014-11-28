<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class DivPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Div";
    }

    public function convert(BinaryOp\Div $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Div', $node->left, ' / ', $node->right);
    }
}
