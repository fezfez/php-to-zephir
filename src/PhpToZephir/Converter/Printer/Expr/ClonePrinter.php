<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ClonePrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Clone';
    }

    public function convert(Expr\Clone_ $node)
    {
        return 'clone '.$this->dispatcher->p($node->expr);
    }
}
