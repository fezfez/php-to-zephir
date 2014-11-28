<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class ObjectPropertyPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pObjectProperty";
    }

    public function convert($node)
    {
        if ($node instanceof Expr) {
            return '{'.$this->dispatcher->p($node).'}';
        } else {
            return $node;
        }
    }
}
