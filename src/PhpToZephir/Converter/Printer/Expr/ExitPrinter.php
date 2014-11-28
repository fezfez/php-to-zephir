<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ExitPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Exit";
    }

    public function convert(Expr\Exit_ $node)
    {
        return 'die'.(null !== $node->expr ? '('.$this->dispatcher->p($node->expr).')' : '');
    }
}
