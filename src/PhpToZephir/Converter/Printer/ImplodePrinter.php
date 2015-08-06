<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\SimplePrinter;

class ImplodePrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pImplode';
    }

    /**
     * @param array  $nodes
     * @param string $glue
     *
     * @return string
     */
    public function convert(array $nodes, $glue = '')
    {
        $pNodes = array();
        foreach ($nodes as $node) {
            $pNodes[] = $this->dispatcher->p($node);
        }

        return implode($glue, $pNodes);
    }
}
