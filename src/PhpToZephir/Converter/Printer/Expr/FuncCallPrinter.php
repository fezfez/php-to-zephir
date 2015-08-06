<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class FuncCallPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_FuncCall';
    }

    /**
     * @param Expr\FuncCall $node
     *
     * @return string
     */
    public function convert(Expr\FuncCall $node)
    {
        if ($node->name instanceof Expr\Variable) {
            return '{'.$this->dispatcher->p($node->name).'}('.$this->dispatcher->pCommaSeparated($node->args).')';
        } else {
            return $this->dispatcher->p($node->name).'('.$this->dispatcher->pCommaSeparated($node->args).')';
        }
    }
}
