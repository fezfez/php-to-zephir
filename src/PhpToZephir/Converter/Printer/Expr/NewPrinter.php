<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class NewPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_New";
    }

    public function convert(Expr\New_ $node)
    {
        return 'new '.$this->dispatcher->p($node->class).'('.$this->dispatcher->pCommaSeparated($node->args).')';
    }
}
