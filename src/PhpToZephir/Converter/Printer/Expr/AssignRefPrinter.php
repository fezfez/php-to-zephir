<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class AssignRefPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_AssignRef';
    }

    /**
     * @param Expr\AssignRef $node
     *
     * @return string
     */
    public function convert(Expr\AssignRef $node)
    {
        $this->logger->logIncompatibility(
            '(=&) AssignRef',
            '(=&) AssignRef does not exist in zephir, assign',
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
        );

        return 'let '.$this->dispatcher->pInfixOp('Expr_AssignRef', $node->var, ' = ', $node->expr);
    }
}
