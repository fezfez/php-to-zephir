<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PreDecPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_PreDec";
    }

    public function convert(Expr\PreDec $node)
    {
        return 'let '.$this->dispatcher->pPostfixOp('Expr_PreDec', $node->var, '--');
    }
}
