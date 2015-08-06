<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node\Expr;
use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpToZephir\ReservedWordReplacer;

class ObjectPropertyPrinter
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

    public static function getType()
    {
        return 'pObjectProperty';
    }

    public function convert($node)
    {
        if ($node instanceof Expr) {
            return '{'.$this->dispatcher->p($node).'}';
        } else {
            return $this->reservedWordReplacer->replace($node);
        }
    }
}
