<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpToZephir\Converter\SimplePrinter;

class StaticCallPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_StaticCall";
    }

    public function convert(Expr\StaticCall $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

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
