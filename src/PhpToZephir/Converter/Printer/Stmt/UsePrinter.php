<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class UsePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Use";
    }

    public function convert(Stmt\Use_ $node)
    {
        return 'use '
             .($node->type === Stmt\Use_::TYPE_FUNCTION ? 'function ' : '')
             .($node->type === Stmt\Use_::TYPE_CONSTANT ? 'const ' : '')
             .$this->dispatcher->pCommaSeparated($node->uses).';';
    }
}
