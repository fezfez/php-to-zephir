<?php

namespace PhpToZephir\Converter\Printer\Name;

use PhpParser\Node\Name;
use PhpToZephir\Converter\SimplePrinter;

class FullyQualifiedPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pName_FullyQualified";
    }

    public function convert(Name\FullyQualified $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return '\\'.implode('\\', $node->parts);
    }
}
