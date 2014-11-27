<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;

class PostfixOpPrinter
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
        return "pPostfixOp";
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     * @param Node[] $node  Array of nodes
     *
     * @return string Pretty printed statements
     */
    public function convert($type, Node $node, $operatorString)
    {
        list($precedence, $associativity) = $this->dispatcher->getPrecedenceMap($type);
        return $this->dispatcher->pPrec($node, $precedence, $associativity, -1) . $operatorString;
    }
}
