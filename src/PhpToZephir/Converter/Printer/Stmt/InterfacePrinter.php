<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\Converter\Manipulator\ClassManipulator;

class InterfacePrinter
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
     * @param Dispatcher           $dispatcher
     * @param Logger               $logger
     * @param ClassManipulator     $classManipulator
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ClassManipulator $classManipulator,
        ReservedWordReplacer $reservedWordReplacer
    ) {
        $this->dispatcher           = $dispatcher;
        $this->logger               = $logger;
        $this->classManipulator     = $classManipulator;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public static function getType()
    {
        return "pStmt_Interface";
    }

    public function convert(Stmt\Interface_ $node)
    {
        $node->name = $this->reservedWordReplacer->replace($node->name);

        $extendsStmt = '';

        if (!empty($node->extends)) {
            $extendsStmt = ' extends ';
            $extends = array();
            foreach ($node->extends as $extend) {
                $extends[] = $this->classManipulator->findRightClass($extend, $this->dispatcher->getMetadata());
            }

            $extendsStmt .= implode(', ', $extends);
        }

        return 'interface '.$node->name
             .$extendsStmt
             ."\n".'{'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
