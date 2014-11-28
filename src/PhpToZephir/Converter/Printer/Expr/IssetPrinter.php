<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class IssetPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Isset";
    }

    public function convert(Expr\Isset_ $node)
    {
        return 'isset '.$this->dispatcher->pCommaSeparated($node->vars).'';
    }
}
