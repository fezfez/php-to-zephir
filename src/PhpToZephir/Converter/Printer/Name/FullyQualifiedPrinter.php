<?php

namespace PhpToZephir\Converter\Printer\Name;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Name;

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
