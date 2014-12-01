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
     * @param Dispatcher $dispatcher
     * @param Logger     $logger
     * @param ReservedWordReplacer     $reservedWordReplacer
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->reservedWordReplacer     = $reservedWordReplacer;
    }
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Property";
    }

    /**
     * @param  Stmt\Property $node
     * @return string
     */
    public function convert(Stmt\Property $node)
    {
        foreach ($node->props as $key => $prop) {
            $prop->name = $this->reservedWordReplacer->replace($prop->name);
            $node->props[$key] = $prop;
        }

        if ($node->props[0]->default instanceof Expr\Array_ && $node->isStatic() === true) {
            $node->type = $node->type - Stmt\Class_::MODIFIER_STATIC;
            $this->dispatcher->moveToNonStaticVar($node->props[0]->name);
            $this->logger->logNode(
                "Static attribute default array not supported in zephir, (see #188). Changed into non static. ",
                $node,
                $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
            );
        }

        return $this->dispatcher->pModifiers($node->type) . $this->dispatcher->pCommaSeparated($node->props) . ';';
    }
}
