<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class EvalPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Eval';
    }

    public function convert(Expr\Eval_ $node)
    {
        return 'eval('.$this->dispatcher->p($node->expr).')';
    }
}
