<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class StaticVarPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_StaticVar";
    }

    /**
     * @param  Stmt\StaticVar $node
     * @return string
     */
    public function convert(Stmt\StaticVar $node)
    {
        return '$'.$node->name
             .(null !== $node->default ? ' = '.$this->dispatcher->p($node->default) : '');
    }
}
