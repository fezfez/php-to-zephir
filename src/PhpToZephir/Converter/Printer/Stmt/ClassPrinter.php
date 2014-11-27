<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpToZephir\converter\Manipulator\ClassManipulator;
use PhpToZephir\ReservedWordReplacer;

class ClassPrinter
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
     * @var ClassManipulator
     */
    private $classManipulator = null;
    /**
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     * @param ClassManipulator $classManipulator
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ClassManipulator $classManipulator,
        ReservedWordReplacer $reservedWordReplacer
    ) {
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
        $this->classManipulator = $classManipulator;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public static function getType()
    {
        return "pStmt_Class";
    }

    public function convert(Stmt\Class_ $node)
    {
        $node->name = $this->reservedWordReplacer->replace($node->name);

        return $this->dispatcher->pModifiers($node->type)
             . 'class ' . $node->name
             . (null !== $node->extends ? ' extends ' . $this->dispatcher->p($node->extends) : '')
             . (!empty($node->implements) ? ' implements ' . $this->dispatcher->pCommaSeparated($node->implements) : '')
             . "\n" . '{' . $this->dispatcher->pStmts($node->stmts) . "\n" . '}';
    }

    /**
     * @param Name[] $nodes
     */
    private function p_implements($nodes)
    {
        $classes = array();

        foreach ($nodes as $node) {
            $classes[] = $this->classManipulator->findRightClass($node, $this->dispatcher->getMetadata());
        }

        return implode(', ', $classes);
    }
}
