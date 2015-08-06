<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class LogicalXorPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_LogicalXor';
    }

    public function convert(BinaryOp\LogicalXor $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_LogicalXor', $node->left, ' xor ', $node->right);
    }
}
