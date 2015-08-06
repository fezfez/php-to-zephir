<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class YieldPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Yield';
    }

    public function convert(Expr\Yield_ $node)
    {
        $this->logger->logNode('Yield does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());

        if ($node->value === null) {
            return 'yield';
        } else {
            // this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
            return '(yield '
                 .($node->key !== null ? $this->dispatcher->p($node->key).' => ' : '')
                 .$this->dispatcher->p($node->value)
                 .')';
        }
    }
}
