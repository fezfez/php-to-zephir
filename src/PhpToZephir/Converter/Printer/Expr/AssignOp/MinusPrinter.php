<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class MinusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_Minus";
    }

    public function convert(AssignOp\Minus $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Minus', $node->var, ' -= ', $node->expr);
    }
}
