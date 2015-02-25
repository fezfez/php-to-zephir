<?php

namespace PhpToZephir\Converter\Printer\Name;

use PhpParser\Node\Name;
use PhpToZephir\Converter\SimplePrinter;

class FullyQualifiedPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pName_FullyQualified";
    }

    /**
     * @param Name\FullyQualified $node
     *
     * @return string
     */
    public function convert(Name\FullyQualified $node)
    {
        return '\\'.implode('\\', $node->parts);
    }
}
