<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PostIncPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_PostInc";
    }

    public function convert(Expr\PostInc $node)
    {
        return 'let '.$this->dispatcher->pPostfixOp('Expr_PostInc', $node->var, '++');
    }
}
