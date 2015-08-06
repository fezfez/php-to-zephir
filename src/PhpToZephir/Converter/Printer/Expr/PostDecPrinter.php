<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PostDecPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_PostDec';
    }

    public function convert(Expr\PostDec $node)
    {
        return 'let '.$this->dispatcher->pPostfixOp('Expr_PostDec', $node->var, '--');
    }
}
