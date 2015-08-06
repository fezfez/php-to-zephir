<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Scalar\String;
use PhpToZephir\Converter\SimplePrinter;

class StringPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Cast_String';
    }

    public function convert(Cast\String $node)
    {
        return $this->dispatcher->pPrefixOp('Expr_Cast_String', '(string) ', $node->expr);
    }
}
