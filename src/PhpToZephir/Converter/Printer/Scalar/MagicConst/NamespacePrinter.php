<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpParser\Node\Scalar\MagicConst;

class NamespacePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_MagicConst_Namespace";
    }

    public function convert(MagicConst\Namespace_ $node)
    {
        return '__NAMESPACE__';
    }
}
