<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class PrefixOpPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pPrefixOp';
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     * @param Node $node Array of nodes
     *
     * @return string Pretty printed statements
     */
    public function convert($type, $operatorString, Node $node)
    {
        list($precedence, $associativity) = $this->dispatcher->getPrecedenceMap($type);

        return $operatorString.$this->dispatcher->pPrec($node, $precedence, $associativity, 1);
    }
}
