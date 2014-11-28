<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;

class FilePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_MagicConst_File";
    }

    public function convert(MagicConst\File $node)
    {
        return '__FILE__';
    }
}
