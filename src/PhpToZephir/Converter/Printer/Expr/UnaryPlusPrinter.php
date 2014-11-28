<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class UnaryPlusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_UnaryPlus";
    }

    public function convert(Expr\UnaryPlus $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_UnaryPlus', '+', $node->expr);
    }
}
