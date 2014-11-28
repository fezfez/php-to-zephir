<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class VarOrNewExprPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pVarOrNewExpr";
    }

    public function convert(Node $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        if ($node instanceof Expr\New_) {
            return '('.$this->dispatcher->p($node).')';
        } else {
            return $this->dispatcher->p($node);
        }
    }
}
