<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ErrorSuppressPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_ErrorSuppress";
    }

    public function convert(Expr\ErrorSuppress $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_ErrorSuppress', '@', $node->expr);
    }
}
