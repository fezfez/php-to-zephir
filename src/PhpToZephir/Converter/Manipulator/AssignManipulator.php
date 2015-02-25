<?php

namespace PhpToZephir\Converter\Manipulator;

use PhpParser\Node\Scalar;
use PhpParser\Node\Expr;
use PhpToZephir\NodeFetcher;
use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;

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
     * @param Dispatcher  $dispatcher
     */
    public function __construct(NodeFetcher $nodeFetcher, Dispatcher $dispatcher)
    {
        $this->nodeFetcher = $nodeFetcher;
        $this->dispatcher  = $dispatcher;
    }

    /**
     * @param mixed $node
     *
     * @return array
     */
    public function collectAssignInCondition($node)
    {
        $collected = array(
            'extracted' => array(),
        );

        foreach ($this->nodeFetcher->foreachNodes($node) as $key => $stmtData) {
            $stmt = $stmtData['node'];
            $collected = $this->extract($stmt, $collected, $stmtData['parentClass']);
        }

        $collected = $this->extract($node, $collected);

        return $collected;
    }

    /**
     * @param mixed  $stmt
     * @param array  $collected
     * @param string $parentClass
     *
     * @return array
     */
    private function extract($stmt, array $collected, $parentClass = '')
    {
        if ($stmt instanceof Expr\Assign) {
            if ($stmt->expr instanceof Expr\BinaryOp) {
                $stmt->expr = $stmt->expr->left;
                $collected['extracted'][] = $this->dispatcher->pExpr_Assign($stmt).";";
            } else {
                $collected['extracted'][] = $this->dispatcher->pExpr_Assign($stmt).";";
            }
        } elseif ($this->isVarModification($stmt)) {
            $collected['extracted'][] = $this->dispatcher->p($stmt).";";
        } elseif ($this->isVarCreation($stmt) && $parentClass != "PhpParser\Node\Expr\ArrayItem") {
            $collected['extracted'][] = 'let tmpArray'.md5(serialize($stmt->items)).' = '.$this->dispatcher->p($stmt).";";
        }

        return $collected;
    }

    /**
     * @param mixed  $primaryNode
     * @param string $parentClass
     *
     * @return mixed
     */
    public function transformAssignInConditionTest($primaryNode, $parentClass = '')
    {
        if ($primaryNode instanceof BinaryOp) {
            // this is yoda ! invert condition
            if ($primaryNode->left instanceof Expr\ConstFetch ||
                ($primaryNode->left instanceof Scalar) !== false ||
                $primaryNode->left instanceof Expr\Array_
            ) {
                $left = $primaryNode->left;
                $right = $primaryNode->right;
                $primaryNode->left = $right;
                $primaryNode->right = $left;
            }
        }

        if ($primaryNode instanceof Expr\Assign) {
            $primaryNode = $primaryNode->var;
        } elseif ($this->isVarModification($primaryNode)) {
            $primaryNode = $primaryNode->var;
        } elseif ($this->isVarCreation($primaryNode) && $parentClass != "PhpParser\Node\Expr\ArrayItem") {
            $primaryNode = new Expr\Variable('tmpArray'.md5(serialize($primaryNode->items)));
        } else {
            if (is_array($primaryNode) === true) {
                foreach ($primaryNode as $key => $node) {
                    $primaryNode[$key] = $this->transformAssignInConditionTest($node);
                }
            } elseif (is_string($primaryNode) === false && method_exists($primaryNode, 'getIterator') === true) {
                foreach ($primaryNode->getIterator() as $key => $node) {
                    $primaryNode->{$key} = $this->transformAssignInConditionTest($node, get_class($primaryNode));
                }
            }
        }

        return $primaryNode;
    }

    /**
     * @param mixed $stmt
     *
     * @return boolean
     */
    private function isVarCreation($stmt)
    {
        return $stmt instanceof Expr\Array_;
    }

    /**
     * @param mixed $stmt
     *
     * @return boolean
     */
    private function isVarModification($stmt)
    {
        return $stmt instanceof Expr\PostDec ||
        $stmt instanceof Expr\PostInc ||
        $stmt instanceof Expr\PreDec ||
        $stmt instanceof Expr\PreInc;
    }
}
