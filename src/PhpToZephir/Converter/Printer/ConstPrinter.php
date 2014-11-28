<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ConstPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pConst";
    }

    /**
     * @param  Node\Const_ $node
     * @return string
     */
    public function convert(Node\Const_ $node)
    {
        return $node->name.' = '.$this->dispatcher->p($node->value);
    }
}
