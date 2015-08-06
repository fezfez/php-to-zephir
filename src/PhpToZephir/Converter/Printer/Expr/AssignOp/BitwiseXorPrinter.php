<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseXorPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_AssignOp_BitwiseXor';
    }

    public function convert(AssignOp\BitwiseXor $node)
    {
        return $this->dispatcher->pInfixOp('Expr_AssignOp_BitwiseXor', $node->var, ' ^= ', $node->expr);
    }
}
