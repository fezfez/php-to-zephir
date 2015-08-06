<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;
use PhpToZephir\Converter\SimplePrinter;

class DirPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pScalar_MagicConst_Dir';
    }

    public function convert(MagicConst\Dir $node)
    {
        return '__DIR__';
    }
}
