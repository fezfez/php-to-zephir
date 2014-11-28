<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class StaticPropertyFetchPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_StaticPropertyFetch";
    }

    public function convert(Expr\StaticPropertyFetch $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $this->dispatcher->p($node->class).'::$'.$this->dispatcher->pObjectProperty($node->name);
    }
}
