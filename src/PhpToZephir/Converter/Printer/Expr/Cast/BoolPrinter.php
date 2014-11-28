<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpToZephir\Converter\SimplePrinter;

class BoolPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Cast_Bool";
    }

    public function convert(Cast\Bool $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_Cast_Bool', '(bool) ', $node->expr);
    }
}
