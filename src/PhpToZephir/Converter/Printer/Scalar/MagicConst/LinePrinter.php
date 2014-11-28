<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;
use PhpToZephir\Converter\SimplePrinter;

class LinePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_MagicConst_Line";
    }

    public function convert(MagicConst\Line $node)
    {
        return '__LINE__';
    }
}
