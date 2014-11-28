<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ShellExecPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_ShellExec";
    }

    public function convert(Expr\ShellExec $node)
    {
        return '`'.$this->dispatcher->pEncapsList($node->parts, '`').'`';
    }
}
