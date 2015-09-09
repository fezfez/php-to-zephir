<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\Manipulator\AssignManipulator;

class TernaryPrinter
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
        return 'pExpr_Ternary';
    }

    /**
     * @param Expr\Ternary $node
     * @param string       $returnAsArray
     *
     * @return string
     */
    public function convert(Expr\Ternary $node, $returnAsArray = false)
    {
        // a bit of cheating: we treat the ternary as a binary op where the ?...: part is the operator.
        // this is okay because the part between ? and : never needs parentheses.

        $collected = $this->assignManipulator->collectAssignInCondition($node->cond);
        $node->cond = $this->assignManipulator->transformAssignInConditionTest($node->cond);

        $collected->setExpr($this->dispatcher->pInfixOp('Expr_Ternary',
            $node->cond, ' ?'.(null !== $node->if ? ' '.$this->dispatcher->p($node->if).' ' : ' ' . $this->dispatcher->p($node->cond)).' : ', $node->else
        ));

        if ($returnAsArray === true) {
            return $collected;
        } else {
            return $collected->getCollected()." ".$collected->getExpr();
        }
    }
}
