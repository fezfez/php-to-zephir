<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\SimplePrinter;

class CommaSeparatedPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pCommaSeparated";
    }

    public function convert(array $nodes)
    {
        return $this->dispatcher->pImplode($nodes, ', ');
    }
}
