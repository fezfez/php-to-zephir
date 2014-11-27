<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class UsePrinter
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
        return "pStmt_Use";
    }

    public function convert(Stmt\Use_ $node)
    {
        return 'use '
             . ($node->type === Stmt\Use_::TYPE_FUNCTION ? 'function ' : '')
             . ($node->type === Stmt\Use_::TYPE_CONSTANT ? 'const ' : '')
             . $this->dispatcher->pCommaSeparated($node->uses) . ';';
    }
}
