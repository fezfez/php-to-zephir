<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class PlusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_Plus";
    }

    public function convert(AssignOp\Plus $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Plus', $node->var, ' += ', $node->expr);
    }
}
