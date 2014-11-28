<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class PlusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Plus";
    }

    public function convert(BinaryOp\Plus $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Plus', $node->left, ' + ', $node->right);
    }
}
