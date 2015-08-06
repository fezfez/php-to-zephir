<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ParamPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pParam';
    }

    /**
     * @param Node\Param $node
     *
     * @return string
     */
    public function convert(Node\Param $node)
    {
        return ($node->type ? (is_string($node->type) ? $node->type : $this->dispatcher->p($node->type)).' ' : '')
             .($node->byRef ? '&' : '')
             .($node->variadic ? '... ' : '')
             .''.$node->name
             .($node->default ? ' = '.$this->dispatcher->p($node->default) : '').'';
    }
}
