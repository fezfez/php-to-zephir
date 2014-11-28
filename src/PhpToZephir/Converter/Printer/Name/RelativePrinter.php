<?php

namespace PhpToZephir\Converter\Printer\Name;

use PhpParser\Node\Name;
use PhpToZephir\Converter\SimplePrinter;

class RelativePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pName_Relative";
    }

    public function convert(Name\Relative $node)
    {
        return 'namespace\\'.implode('\\', $node->parts);
    }
}
