<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\Converter\Dispatcher;

class ClosureUsePrinter
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
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public static function getType()
    {
        return "pExpr_ClosureUse";
    }

    public function convert(Expr\ClosureUse $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        if ($node->byRef) {
            $this->logger->logNode(
                "Zephir not support reference parameters for now. Stay tuned for https://github.com/phalcon/zephir/issues/203",
                $node,
                $this->dispatcher->getMetadata()->getClass()
            );
        }

        return $this->reservedWordReplacer->replace($node->var);
    }
}
