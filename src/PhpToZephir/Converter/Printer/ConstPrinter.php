<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ConstPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pConst";
    }

    /**
     * @param  Node\Const_ $node
     * @return string
     */
    public function convert(Node\Const_ $node)
    {
        if ($node->value instanceof Node\Expr\Array_) {
            $this->logger->logNode(
                'Array not supported in const, transform as empty string (see #188)',
                $node,
                $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
            );
            $node->value = new Node\Scalar\String('');
        }

        return $node->name.' = '.$this->dispatcher->p($node->value);
    }
}
