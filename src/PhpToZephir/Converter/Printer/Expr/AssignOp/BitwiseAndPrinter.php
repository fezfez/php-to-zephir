<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;

class BitwiseAndPrinter
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
        return "pExpr_AssignOp_BitwiseAnd";
    }

    public function convert(AssignOp\BitwiseAnd $node) {
        $this->logger->logNode(
            '(&=) BitwiseAnd does not exist in zephir, assign',
            $node,
            $this->dispatcher->getMetadata()->getClass()
        );
        return 'let ' . $this->dispatcher->pInfixOp('Expr_AssignOp_BitwiseAnd', $node->var, ' = ', $node->expr);
    }
}
