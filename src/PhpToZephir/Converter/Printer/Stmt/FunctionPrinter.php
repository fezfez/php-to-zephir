<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class FunctionPrinter
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
        return "pStmt_Function";
    }

    public function convert(Stmt\Function_ $node) {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->dispatcher->pCommaSeparated($node->params) . ')'
             . "\n" . '{' . $this->dispatcher->pStmts($node->stmts) . "\n" . '}';
    }
}
