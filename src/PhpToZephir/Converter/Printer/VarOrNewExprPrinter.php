<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class VarOrNewExprPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pVarOrNewExpr";
    }

    /**
     * @param Node $node
     *
     * @return string
     */
    public function convert(Node $node)
    {
        if ($node instanceof Expr\New_) {
            return '('.$this->dispatcher->p($node).')';
        } else {
            return $this->dispatcher->p($node);
        }
    }
}
