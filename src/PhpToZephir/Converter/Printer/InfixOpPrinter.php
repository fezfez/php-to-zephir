<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;

class InfixOpPrinter
{
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;
    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger)
    {
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
    }

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
             . $operatorString
             . $this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1);
    }
}
