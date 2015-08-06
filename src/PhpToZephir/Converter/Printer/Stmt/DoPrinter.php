<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\Manipulator\AssignManipulator;

class DoPrinter
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
     * @param Dispatcher $dispatcher
     * @param Logger     $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, AssignManipulator $assignManipulator)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->assignManipulator = $assignManipulator;
    }

    public static function getType()
    {
        return 'pStmt_Do';
    }

    public function convert(Stmt\Do_ $node)
    {
        $condition = clone $node;
        $collected = $this->assignManipulator->collectAssignInCondition($condition->cond);
        $collected = !empty($collected['extracted']) ? "\n".implode("\n", $collected['extracted']) : '';
        $node->cond = $this->assignManipulator->transformAssignInConditionTest($node->cond);

        return 'do {'.$this->dispatcher->pStmts($node->stmts).$collected."\n"
             .'} while ('.$this->dispatcher->p($node->cond).');';
    }
}
