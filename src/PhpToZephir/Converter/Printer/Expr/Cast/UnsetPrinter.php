<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpToZephir\Converter\SimplePrinter;

class UnsetPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_Cast_Unset";
    }

    public function convert(Cast\Unset_ $node)
    {
        $this->logger->logNode('(unset) does not exist in zephir, remove cast', $node, $this->dispatcher->getMetadata()->getClass());

        return $this->dispatcher->pPrefixOp('Expr_Cast_Unset', '', $node->expr);
    }
}
