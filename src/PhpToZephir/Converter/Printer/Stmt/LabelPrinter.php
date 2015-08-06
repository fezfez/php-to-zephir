<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class LabelPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_Label';
    }

    /**
     * @param Stmt\Label $node
     *
     * @return string
     */
    public function convert(Stmt\Label $node)
    {
        return $node->name.':';
    }
}
