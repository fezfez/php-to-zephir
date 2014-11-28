<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class ModPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_Mod";
    }

    public function convert(AssignOp\Mod $node)
    {
        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Mod', $node->var, ' %= ', $node->expr);
    }
}
