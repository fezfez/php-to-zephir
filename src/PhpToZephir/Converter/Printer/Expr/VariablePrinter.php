<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpToZephir\ReservedWordReplacer;

class VariablePrinter
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
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public static function getType()
    {
        return "pExpr_Variable";
    }

    public function convert(Expr\Variable $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
        if ($node->name instanceof Expr) {
            return '{' . $this->dispatcher->p($node->name) . '}';
        } else {
            return '' . $this->reservedWordReplacer->replace($node->name);
        }
    }
}
