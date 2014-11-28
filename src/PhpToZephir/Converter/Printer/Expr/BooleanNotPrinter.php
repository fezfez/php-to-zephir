<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class BooleanNotPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BooleanNot";
    }

    public function convert(Expr\BooleanNot $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_BooleanNot', '!', $node->expr);
    }
}
