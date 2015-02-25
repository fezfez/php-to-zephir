<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpParser\Node\Scalar;
use PhpToZephir\Converter\SimplePrinter;

class EncapsedPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pScalar_Encapsed";
    }

    /**
     * @param Scalar\Encapsed $node
     *
     * @return string
     */
    public function convert(Scalar\Encapsed $node)
    {
        return '"'.$this->dispatcher->pEncapsList($node->parts, '"').'"';
    }
}
