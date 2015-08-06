<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class StaticCallPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_StaticCall';
    }

    /**
     * @param Expr\StaticCall $node
     *
     * @return string
     */
    public function convert(Expr\StaticCall $node)
    {
        return (($node->class instanceof Expr\Variable) ? '{'.$this->dispatcher->p($node->class).'}' : $this->dispatcher->p($node->class)).'::'
             .($node->name instanceof Expr
                ? ($node->name instanceof Expr\Variable
                   || $node->name instanceof Expr\ArrayDimFetch
                   ? $this->dispatcher->p($node->name)
                   : '{'.$this->dispatcher->p($node->name).'}')
                : $node->name)
             .'('.$this->dispatcher->pCommaSeparated($node->args).')';
    }
}
