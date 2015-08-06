<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PropertyFetchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_PropertyFetch';
    }

    /**
     * @param Expr\PropertyFetch $node
     *
     * @return string
     */
    public function convert(Expr\PropertyFetch $node)
    {
        return $this->dispatcher->pVarOrNewExpr($node->var).'->'.$this->dispatcher->pObjectProperty($node->name);
    }
}
