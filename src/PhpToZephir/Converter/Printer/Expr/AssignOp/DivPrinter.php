<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class DivPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_Div";
    }

    public function convert(AssignOp\Div $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Div', $node->var, ' /= ', $node->expr);
    }
}
