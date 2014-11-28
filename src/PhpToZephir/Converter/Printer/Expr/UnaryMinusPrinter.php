<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class UnaryMinusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_UnaryMinus";
    }

    public function convert(Expr\UnaryMinus $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_UnaryMinus', '-', $node->expr);
    }
}
