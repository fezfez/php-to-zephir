<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class DeclareDeclarePrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_DeclareDeclare';
    }

    /**
     * @param Stmt\DeclareDeclare $node
     *
     * @return string
     */
    public function convert(Stmt\DeclareDeclare $node)
    {
        return $node->key.' = '.$this->dispatcher->p($node->value);
    }
}
