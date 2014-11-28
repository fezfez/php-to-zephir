<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class PropertyPropertyPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_PropertyProperty";
    }

    /**
     * @param  Stmt\PropertyProperty $node
     * @return string
     */
    public function convert(Stmt\PropertyProperty $node)
    {
        return '$'.$node->name
             .(null !== $node->default ? ' = '.$this->dispatcher->p($node->default) : '');
    }
}
