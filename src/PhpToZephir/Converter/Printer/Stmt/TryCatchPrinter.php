<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class TryCatchPrinter
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
        return "pStmt_TryCatch";
    }

    public function convert(Stmt\TryCatch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
        return 'try {' . $this->dispatcher->pStmts($node->stmts) . "\n" . '}'
             . $this->dispatcher->pImplode($node->catches)
             . ($node->finallyStmts !== null
                ? ' finally {' . $this->dispatcher->pStmts($node->finallyStmts) . "\n" . '}'
                : '');
    }
}
