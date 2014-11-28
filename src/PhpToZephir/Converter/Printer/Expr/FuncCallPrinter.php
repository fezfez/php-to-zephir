<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpToZephir\Converter\SimplePrinter;

class FuncCallPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_FuncCall";
    }

    public function convert(Expr\FuncCall $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        if ($node->name instanceof Expr\Variable) {
            return '{'.$this->dispatcher->p($node->name).'}('.$this->dispatcher->pCommaSeparated($node->args).')';
        } else {
            return $this->dispatcher->p($node->name).'('.$this->dispatcher->pCommaSeparated($node->args).')';
        }
    }
}
