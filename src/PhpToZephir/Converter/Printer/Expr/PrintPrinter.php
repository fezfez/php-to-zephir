<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PrintPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Print";
    }

    public function convert(Expr\Print_ $node)
    {
        return 'print '.$this->dispatcher->p($node->expr);
    }
}
