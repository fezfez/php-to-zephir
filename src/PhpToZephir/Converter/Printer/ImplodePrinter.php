<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Converter\SimplePrinter;

class ImplodePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pImplode";
    }

    public function convert(array $nodes, $glue = '')
    {
        $pNodes = array();
        foreach ($nodes as $node) {
            $pNodes[] = $this->dispatcher->p($node);
        }

        return implode($glue, $pNodes);
    }
}
