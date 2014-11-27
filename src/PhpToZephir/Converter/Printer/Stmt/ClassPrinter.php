<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use phpDocumentor\Reflection\DocBlock;
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
             . (null !== $node->extends ? ' extends ' . $this->classManipulator->findRightClass($node->extends, $this->dispatcher->getMetadata()) : '')
             . (!empty($node->implements) ? ' implements ' . $this->p_implements($node->implements) : '')
             . "\n" . '{' . $this->dispatcher->pStmts($node->stmts) . "\n" . '}';
    }

    private function p_implements($nodes)
    {
        $classes = array();

        foreach ($nodes as $node) {
            $classes[] = $this->classManipulator->findRightClass($node, $this->dispatcher->getMetadata());
        }

        return implode(', ', $classes);
    }
}
