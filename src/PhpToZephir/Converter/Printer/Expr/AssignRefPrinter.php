<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class AssignRefPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignRef";
    }

    public function convert(Expr\AssignRef $node)
    {
        $this->logger->logNode('(=&) AssignRef does not exist in zephir, assign', $node, $this->dispatcher->getMetadata()->getClass());

        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignRef', $node->var, ' = ', $node->expr);
    }
}
