<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ArgPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pArg";
    }

    public function convert(Node\Arg $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return ($node->byRef ? '&' : '').($node->unpack ? '...' : '').$this->dispatcher->p($node->value);
    }
}
