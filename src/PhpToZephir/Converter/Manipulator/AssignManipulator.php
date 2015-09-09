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
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param mixed $node
     *
     * @return ArrayDto
     */
    public function collectAssignInCondition($node, ArrayDto $arrayDto = null)
    {
        if ($arrayDto === null) {
            $arrayDto = new ArrayDto();
        }

        foreach ($this->nodeFetcher->foreachNodes($node) as $stmtData) {
            $collected = $this->extract($stmtData['node'], $arrayDto, $stmtData['parentClass']);
        }

        return $this->extract($node, $arrayDto);
    }

    /**
     * @param mixed  $stmt
     * @param ArrayDto  $arrayDto
     * @param string $parentClass
     *
     * @return array
     */
    private function extract($stmt, ArrayDto $arrayDto, $parentClass = '')
    {
        if ($stmt instanceof Expr\Assign) {
            if ($stmt->expr instanceof Expr\BinaryOp) {
                $stmt->expr = $stmt->expr->left;
                $arrayDto->addCollected($this->dispatcher->pExpr_Assign($stmt, false));
            } else {
                $arrayDto->addCollected($this->dispatcher->pExpr_Assign($stmt, false));
            }
        } elseif ($this->isVarModification($stmt)) {
            $arrayDto->addCollected($this->dispatcher->p($stmt));
        } elseif ($this->isVarCreation($stmt) && $parentClass != "PhpParser\Node\Expr\ArrayItem" && $parentClass != "PhpParser\Node\Expr\Assign") {
            $arrayDto->addCollected('let tmpArray'.md5(serialize($stmt->items)).' = '.$this->dispatcher->p($stmt));
        }

        return $arrayDto;
    }

    /**
     * @param mixed  $primaryNode
     * @param string $parentClass
     *
     * @return mixed
     */
    public function transformAssignInConditionTest($primaryNode, $parentClass = '')
    {
        if ($primaryNode instanceof BinaryOp && ($primaryNode instanceof BinaryOp\Concat === false)) {
            // this is yoda ! invert condition
            if ($primaryNode->left instanceof Expr\ConstFetch ||
                $primaryNode->left instanceof Scalar\String_ ||
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
            } elseif (is_object($primaryNode) === true && !empty($primaryNode->getSubNodeNames())) {
                foreach ($primaryNode->getSubNodeNames() as $key) {
                    $primaryNode->$key = $this->transformAssignInConditionTest($primaryNode->$key, get_class($primaryNode));
                }
            }
        }

        return $primaryNode;
    }

    /**
     * @param mixed $stmt
     *
     * @return bool
     */
    private function isVarCreation($stmt)
    {
        return $stmt instanceof Expr\Array_;
    }

    /**
     * @param mixed $stmt
     *
     * @return bool
     */
    private function isVarModification($stmt)
    {
        return $stmt instanceof Expr\PostDec ||
        $stmt instanceof Expr\PostInc ||
        $stmt instanceof Expr\PreDec ||
        $stmt instanceof Expr\PreInc;
    }
}
