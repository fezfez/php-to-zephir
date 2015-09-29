<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ArgPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pArg';
    }

    /**
     * @param Node\Arg $node
     *
     * @return string
     */
    public function convert(Node\Arg $node)
    {
        if ($node->byRef) {
            $this->logger->logIncompatibility(
                'reference',
                'Reference not supported',
                $node,
                $this->dispatcher->getMetadata()->getClass()
            );
        }

        return ($node->unpack ? '...' : '').$this->dispatcher->p($node->value);
    }
}
