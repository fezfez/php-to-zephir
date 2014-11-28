<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\SimplePrinter;

class CommaSeparatedPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pCommaSeparated";
    }

    /**
     * @param  array  $nodes
     * @return string
     */
    public function convert(array $nodes)
    {
        return $this->dispatcher->pImplode($nodes, ', ');
    }
}
