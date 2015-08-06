<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpToZephir\Converter\SimplePrinter;

class ArrayPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Cast_Array';
    }

    public function convert(Cast\Array_ $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_Cast_Array', '(array) ', $node->expr);
    }
}
