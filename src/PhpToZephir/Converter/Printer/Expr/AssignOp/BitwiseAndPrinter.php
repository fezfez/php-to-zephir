<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;

class BitwiseAndPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_AssignOp_BitwiseAnd";
    }

    public function convert(AssignOp\BitwiseAnd $node)
    {
        $this->logger->logNode(
            '(&=) BitwiseAnd does not exist in zephir, assign',
            $node,
            $this->dispatcher->getMetadata()->getClass()
        );

        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_BitwiseAnd', $node->var, ' = ', $node->expr);
    }
}
