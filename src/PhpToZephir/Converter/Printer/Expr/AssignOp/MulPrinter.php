<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class MulPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_AssignOp_Mul';
    }

    public function convert(AssignOp\Mul $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Mul', $node->var, ' *= ', $node->expr);
    }
}
