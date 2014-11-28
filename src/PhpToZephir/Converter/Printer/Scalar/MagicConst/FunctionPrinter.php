<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;
use PhpToZephir\Converter\SimplePrinter;

class FunctionPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_MagicConst_Function";
    }

    public function convert(MagicConst\Function_ $node)
    {
        return '__FUNCTION__';
    }
}
