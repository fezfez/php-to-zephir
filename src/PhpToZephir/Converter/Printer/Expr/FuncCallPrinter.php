<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

class FuncCallPrinter
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
        return "pExpr_FuncCall";
    }

    public function convert(Expr\FuncCall $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        if($node->name instanceof Expr\Variable) {
            return '{' . $this->dispatcher->p($node->name) . '}(' . $this->dispatcher->pCommaSeparated($node->args) . ')';
        } else {
            return $this->dispatcher->p($node->name) . '(' . $this->dispatcher->pCommaSeparated($node->args) . ')';
        }
    }
}
