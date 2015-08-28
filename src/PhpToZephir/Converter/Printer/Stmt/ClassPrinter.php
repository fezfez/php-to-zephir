<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpToZephir\Converter\Manipulator\ClassManipulator;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\NodeFetcher;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\Array_;

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
     * @var NodeFetcher
     */
    private $nodeFetcher = null;

    /**
     * @param Dispatcher           $dispatcher
     * @param Logger               $logger
     * @param ClassManipulator     $classManipulator
     * @param ReservedWordReplacer $reservedWordReplacer
     * @param NodeFetcher          $nodeFetcher
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ClassManipulator $classManipulator,
        ReservedWordReplacer $reservedWordReplacer,
        NodeFetcher $nodeFetcher
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->classManipulator = $classManipulator;
        $this->reservedWordReplacer = $reservedWordReplacer;
        $this->nodeFetcher = $nodeFetcher;
    }

    public static function getType()
    {
        return 'pStmt_Class';
    }

    public function convert(Stmt\Class_ $node)
    {
        $this->classManipulator->registerClassImplements($node);

        $node->name = $this->reservedWordReplacer->replace($node->name);

        $addArrayPlusMethod = false;

        foreach ($this->nodeFetcher->foreachNodes($node->stmts) as $stmt) {
            if ($stmt['node'] instanceof AssignOp\Plus && $stmt['node']->expr instanceof Array_) {
            	$addArrayPlusMethod = true;
            	break;
            }
        }

        return $this->dispatcher->pModifiers($node->type)
             .'class '.$node->name
             .(null !== $node->extends ? ' extends '.$this->dispatcher->p($node->extends) : '')
             .(!empty($node->implements) ? ' implements '.$this->dispatcher->pCommaSeparated($node->implements) : '')
             ."\n".'{'.$this->dispatcher->pStmts($node->stmts)."\n" . ($addArrayPlusMethod === true ? $this->printArrayPlusMethod() : '').'}';
    }
    
    private function printArrayPlusMethod()
    {
        return '    private function array_plus(array1, array2)
    {
        var union, key, value;
        let union = array1;
        for key, value in array2 {
            if false === array_key_exists(key, union) {
                let union[key] = value;
            }
        }
        
        return union;
    }
';
    }
}
