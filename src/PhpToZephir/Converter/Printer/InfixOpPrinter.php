<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class InfixOpPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pInfixOp";
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     *
     * @return string Pretty printed statements
     */
    public function convert($type, Node $leftNode, $operatorString, Node $rightNode)
    {
        list($precedence, $associativity) = $this->dispatcher->getPrecedenceMap($type);

        return $this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1)
             .$operatorString
             .$this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1);
    }
}
