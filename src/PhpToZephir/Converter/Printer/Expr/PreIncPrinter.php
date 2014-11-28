<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PreIncPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_PreInc";
    }

    public function convert(Expr\PreInc $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return 'let '.$this->dispatcher->pPostfixOp('Expr_PostInc', $node->var, '++');
    }
}
