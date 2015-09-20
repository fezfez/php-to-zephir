<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class IncludePrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_Include';
    }

    public function convert(Expr\Include_ $node)
    {
        static $map = array(
            Expr\Include_::TYPE_INCLUDE      => 'include',
            Expr\Include_::TYPE_INCLUDE_ONCE => 'include_once',
            Expr\Include_::TYPE_REQUIRE      => 'require',
            Expr\Include_::TYPE_REQUIRE_ONCE => 'require_once',
        );
    
        return $map[$node->type] . ' ' . $this->dispatcher->p($node->expr);
    }
}
