<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ArrayItemPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_ArrayItem';
    }

    /**
     * @param Expr\ArrayItem $node
     *
     * @return string
     */
    public function convert(Expr\ArrayItem $node)
    {
        if ($node->byRef) {
            $this->logger->logIncompatibility(
                'reference',
                'Reference not supported',
                $node,
                $this->dispatcher->getMetadata()->getClass()
            );
        }

        return (null !== $node->key ? $this->dispatcher->p($node->key).' : ' : '')
             .$this->dispatcher->p($node->value);
    }
}
