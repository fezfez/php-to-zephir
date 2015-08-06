<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ClassConstFetchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_ClassConstFetch';
    }

    /**
     * @param Expr\ClassConstFetch $node
     *
     * @return string
     */
    public function convert(Expr\ClassConstFetch $node)
    {
        return $this->dispatcher->p($node->class).'::'.$node->name;
    }
}
