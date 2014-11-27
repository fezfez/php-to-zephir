<?php

namespace PhpToZephir\Converter\Printer\Stmt\TraitUseAdaptation;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class PrecedencePrinter
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
        return "pStmt_TraitUseAdaptation_Precedence";
    }

    public function convert(Stmt\TraitUseAdaptation\Precedence $node) {
        return $this->dispatcher->p($node->trait) . '::' . $node->method
             . ' insteadof ' . $this->dispatcher->pCommaSeparated($node->insteadof) . ';';
    }
}
