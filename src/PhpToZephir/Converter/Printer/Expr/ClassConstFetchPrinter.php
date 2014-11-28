<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ClassConstFetchPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_ClassConstFetch";
    }

    public function convert(Expr\ClassConstFetch $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $this->dispatcher->p($node->class).'::'.$node->name;
    }
}
