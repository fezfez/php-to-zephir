<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class EmptyPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Empty";
    }

    public function convert(Expr\Empty_ $node)
    {
        return 'empty('.$this->dispatcher->p($node->expr).')';
    }
}
