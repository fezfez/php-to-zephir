<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\Manipulator\AssignManipulator;

class ArrayPrinter
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
        $this->logger = $logger;
        $this->assignManipulator = $assignManipulator;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return 'pExpr_Array';
    }

    /**
     * @param Expr\Array_ $node
     * @param bool        $returnAsArray
     *
     * @return string|array
     */
    public function convert(Expr\Array_ $node, $returnAsArray = false)
    {
        $collected = $this->assignManipulator->collectAssignInCondition($node->items);
        $node->items = $this->assignManipulator->transformAssignInConditionTest($node->items);

        $collected->setExpr('['.$this->dispatcher->pCommaSeparated($node->items).']');

        if ($returnAsArray === true) {
            return $collected;
        } else {
            return $collected->getCollected().$collected->getExpr();
        }
    }
}
