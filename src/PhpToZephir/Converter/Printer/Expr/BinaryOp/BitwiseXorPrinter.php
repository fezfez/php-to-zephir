<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseXorPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_BitwiseXor';
    }

    public function convert(BinaryOp\BitwiseXor $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_BitwiseXor', $node->left, ' ^ ', $node->right);
    }
}
