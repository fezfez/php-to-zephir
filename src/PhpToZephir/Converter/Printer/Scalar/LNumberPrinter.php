<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpParser\Node\Scalar;
use PhpToZephir\Converter\SimplePrinter;

class LNumberPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pScalar_LNumber";
    }

    /**
     * @param  Scalar\LNumber $node
     * @return string
     */
    public function convert(Scalar\LNumber $node)
    {
        return $node->value;
    }
}
