<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class InstanceofPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Instanceof";
    }

    public function convert(Expr\Instanceof_ $node)
    {
        return $this->dispatcher->pInfixOp('Expr_Instanceof', $node->expr, ' instanceof ', $node->class);
    }
}
