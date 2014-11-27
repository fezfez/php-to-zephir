<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\ReservedWordReplacer;

class UseUsePrinter
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

    public function __construct(Dispatcher $dispatcher, Logger $logger, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->dispatcher           = $dispatcher;
        $this->logger               = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public static function getType()
    {
        return "pStmt_UseUse";
    }

    public function convert(Stmt\UseUse $node)
    {
        if ($node->name->getLast() !== $node->alias) {
            $this->classesAlias[$node->alias] = $this->reservedWordReplacer->replace(implode('\\', $node->name->parts));
        }
        return ''; $this->dispatcher->p($node->name)
             . ($node->name->getLast() !== $node->alias ? ' as ' . $node->alias : '');
    }
}
