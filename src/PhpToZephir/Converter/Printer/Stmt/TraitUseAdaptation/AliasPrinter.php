<?php

namespace PhpToZephir\Converter\Printer\Stmt\TraitUseAdaptation;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class AliasPrinter
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
        return "pStmt_TraitUseAdaptation_Alias";
    }

    public function convert(Stmt\TraitUseAdaptation\Alias $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->fullClass);
    }
}
