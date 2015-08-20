<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpToZephir\ReservedWordReplacer;

class PropertyPrinter
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
     * @param Dispatcher           $dispatcher
     * @param Logger               $logger
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_Property';
    }

    /**
     * @param Stmt\Property $node
     *
     * @return string
     */
    public function convert(Stmt\Property $node)
    {
        foreach ($node->props as $key => $prop) {
            $prop->name = $this->reservedWordReplacer->replace($prop->name);
            $node->props[$key] = $prop;
        }

        return $this->dispatcher->pModifiers($node->type).$this->dispatcher->pCommaSeparated($node->props).';';
    }
}
