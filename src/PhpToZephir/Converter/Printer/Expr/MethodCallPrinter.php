<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpToZephir\converter\Manipulator\AssignManipulator;

class MethodCallPrinter
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
        $this->logger     = $logger;
        $this->assignManipulator = $assignManipulator;
    }

    public static function getType()
    {
        return "pExpr_MethodCall";
    }

    public function convert(Expr\MethodCall $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $collected = $this->assignManipulator->collectAssignInCondition($node->args);
        $node->args = $this->assignManipulator->transformAssignInConditionTest($node->args);

        return (!empty($collected['extracted']) ? implode(";\n", $collected['extracted'])."\n" : '').
                $this->dispatcher->pVarOrNewExpr($node->var).'->'.$this->dispatcher->pObjectProperty($node->name)
             .'('.$this->dispatcher->pCommaSeparated($node->args).')';
    }
}
