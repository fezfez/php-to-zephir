<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpToZephir\Converter\SimplePrinter;

class IntPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Cast_Int';
    }

    public function convert(Cast\Int $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_Cast_Int', '(int) ', $node->expr);
    }
}
