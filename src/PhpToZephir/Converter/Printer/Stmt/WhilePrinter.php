<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\converter\Manipulator\AssignManipulator;

class WhilePrinter
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
     * @var AssignManipulator
     */
    private $assignManipulator = null;

    /**
     * @param Dispatcher        $dispatcher
     * @param Logger            $logger
     * @param AssignManipulator $assignManipulator
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, AssignManipulator $assignManipulator)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->assignManipulator = $assignManipulator;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_While";
    }

    /**
     * @param  Stmt\While_ $node
     * @return string
     */
    public function convert(Stmt\While_ $node)
    {
        $collected  = $this->assignManipulator->collectAssignInCondition($node->cond);
        $node->cond = $this->assignManipulator->transformAssignInConditionTest($node->cond);

        return implode(";\n", $collected['extracted'])."\n".'while ('.$this->dispatcher->p($node->cond).') {'
             .$this->dispatcher->pStmts($node->stmts)."\n".implode(";\n", $collected['extracted'])."\n".'}';
    }
}
