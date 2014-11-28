<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpToZephir\Converter\SimplePrinter;

class DoublePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Cast_Double";
    }

    public function convert(Cast\Double $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_Cast_Double', '(double) ', $node->expr);
    }
}
