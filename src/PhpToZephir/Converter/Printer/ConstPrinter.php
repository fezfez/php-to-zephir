<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ConstPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pConst";
    }

    public function convert(Node\Const_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $node->name.' = '.$this->dispatcher->p($node->value);
    }
}
