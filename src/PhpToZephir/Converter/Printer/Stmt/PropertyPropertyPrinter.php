<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class PropertyPropertyPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_PropertyProperty";
    }

    public function convert(Stmt\PropertyProperty $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return '$'.$node->name
             .(null !== $node->default ? ' = '.$this->dispatcher->p($node->default) : '');
    }
}
