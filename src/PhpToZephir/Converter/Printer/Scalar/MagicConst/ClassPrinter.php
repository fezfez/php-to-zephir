<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;
use PhpToZephir\Converter\SimplePrinter;

class ClassPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_MagicConst_Class";
    }

    public function convert(MagicConst\Class_ $node)
    {
        return '__CLASS__';
    }
}
