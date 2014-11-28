<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ConstFetchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pExpr_ConstFetch";
    }

    /**
     * @param  Expr\ConstFetch $node
     * @return string
     */
    public function convert(Expr\ConstFetch $node)
    {
        return implode('\\', $node->name->parts);
    }
}
