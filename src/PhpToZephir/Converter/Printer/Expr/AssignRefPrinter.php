<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;

class AssignRefPrinter
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
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
    }

    public static function getType()
    {
        return "pExpr_AssignRef";
    }

    public function convert(Expr\AssignRef $node) {
        $this->logger->logNode('(=&) AssignRef does not exist in zephir, assign', $node, $this->fullClass);
        return 'let ' . $this->dispatcher->pInfixOp('Expr_AssignRef', $node->var, ' = ', $node->expr);
    }
}
