<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PropertyFetchPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_PropertyFetch";
    }

    public function convert(Expr\PropertyFetch $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $this->dispatcher->pVarOrNewExpr($node->var).'->'.$this->dispatcher->pObjectProperty($node->name);
    }
}
