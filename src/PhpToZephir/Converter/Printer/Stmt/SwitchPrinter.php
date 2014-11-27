<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\Node\Scalar\String;

class SwitchPrinter
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
        return "pStmt_Switch";
    }

    public function convert(Stmt\Switch_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $transformToIf = false;
        foreach ($node->cases as $case) {
            if (($case->cond instanceof \PhpParser\Node\Scalar\String) === false && $case->cond !== null) {
                $transformToIf = true;
            }
        }

        if ($transformToIf === true) {
            return $this->dispatcher->convertSwitchToIfelse($node);
        } else {
            return 'switch (' . $this->dispatcher->p($node->cond) . ') {'
             . $this->dispatcher->pStmts($node->cases) . "\n" . '}';
        }
    }
}
