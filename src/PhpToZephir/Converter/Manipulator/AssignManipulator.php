<?php

namespace PhpToZephir\converter\Manipulator;

use PhpToZephir\NodeFetcher;
use PhpToZephir\Converter\Dispatcher;

class AssignManipulator
{
    /**
     * @var NodeFetcher
     */
    private $nodeFetcher = null;
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;

    /**
     * @param NodeFetcher $nodeFetcher
     */
    public function __construct(NodeFetcher $nodeFetcher, Dispatcher $dispatcher)
    {
        $this->nodeFetcher = $nodeFetcher;
        $this->dispatcher = $dispatcher;
    }

    public function collectAssignInCondition($node)
    {
        $collected = array(
            'extracted' => array()
        );

        foreach ($this->nodeFetcher->foreachNodes($node) as $key => $stmt) {
            if ($stmt instanceof Assign) {
                if ($stmt->expr instanceof BinaryOp) {
                    $stmt->expr = $stmt->expr->left;
                    $collected['extracted'][] = $this->dispatcher->pExpr_Assign($stmt) . ";";
                } else {
                    $collected['extracted'][] = $this->dispatcher->pExpr_Assign($stmt) . ";";
                }
            } elseif ($this->isVarModification($stmt)) {
                $collected['extracted'][] = $this->dispatcher->p($stmt) . ";";
            }
        }

        return $collected;
    }

    public function transformAssignInConditionTest($primaryNode)
    {
        if ($primaryNode instanceof Expr\Assign) {
            $primaryNode = $primaryNode->var;
        } elseif ($this->isVarModification($primaryNode)) {
            $primaryNode = $primaryNode->var;
        } else {
            if (is_array($primaryNode) === true) {
                foreach ($primaryNode as $key => $node) {
                    $primaryNode[$key] = $this->transformAssignInConditionTest($node);
                }
            } elseif (is_string($primaryNode) === false && method_exists($primaryNode, 'getIterator') === true) {
                foreach ($primaryNode->getIterator() as $key => $node) {
                    $primaryNode->{$key} = $this->transformAssignInConditionTest($node);
                }
            }
        }

        return $primaryNode;
    }

    private function isVarModification($stmt)
    {
        return $stmt instanceof Expr\PostDec ||
        $stmt instanceof Expr\PostInc ||
        $stmt instanceof Expr\PreDec ||
        $stmt instanceof Expr\PreInc;
    }
}