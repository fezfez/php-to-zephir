<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class StaticPropertyFetchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_StaticPropertyFetch';
    }

    /**
     * @param Expr\StaticPropertyFetch $node
     *
     * @return string
     */
    public function convert(Expr\StaticPropertyFetch $node)
    {
        return $this->dispatcher->p($node->class).'::'.$this->dispatcher->pObjectProperty($node->name);
    }
}
