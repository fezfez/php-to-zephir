<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseOrPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_BitwiseOr";
    }

    public function convert(AssignOp\BitwiseOr $node)
    {
        return 'let '.$this->dispatcher->p($node->var).' = '.$this->dispatcher->p($node->var).' | '.$this->dispatcher->p($node->expr);
    }
}
