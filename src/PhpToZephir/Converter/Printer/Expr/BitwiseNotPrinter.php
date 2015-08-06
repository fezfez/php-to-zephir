<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseNotPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BitwiseNot';
    }

    public function convert(Expr\BitwiseNot $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_BitwiseNot', '~', $node->expr);
    }
}
