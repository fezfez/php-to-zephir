<?php

namespace PhpToZephir\converter\Manipulator;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpToZephir\Converter\Dispatcher;

class ArrayManipulator
{
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ArrayDimFetch $node
     */
    private function findComplexArrayDimFetch($node, $collected = array())
    {
        if ($this->isInvalidInArrayDimFetch($node) === true) {
            if ($node->dim instanceof FuncCall) {
                $this->logger->trace(__METHOD__ . ' ' . __LINE__ . ' Non supported funccall in array', $node, $this->fullClass);
            } else {
                $collected[] = array(
                    'expr' => $this->dispatcher->p($node->dim) . ";\n",
                    'splitTab' => true,
                    'var' => $this->dispatcher->p($node->dim->var)
                );
            }
        } else {
            if ($node->dim === null) {
                $collected[] = array('expr' => $this->dispatcher->p($node->var), 'splitTab' => false);
            } else {
                $collected[] = array('expr' => $this->dispatcher->p($node->dim), 'splitTab' => false);
            }
        }

        if ($node->var instanceof ArrayDimFetch) {
            $collected = $this->findComplexArrayDimFetch($node->var, $collected);
        } else {
            $collected[] = $node->var;
        }

        return $collected;
    }

    private function isInvalidInArrayDimFetch($node)
    {
        if ($node->dim instanceof Concat) {
            return $this->isInvalidInArrayDimFetch($node->dim->left)
            && $this->isInvalidInArrayDimFetch($node->dim->right);
        } else {
            return $this->isInvalidIn($node->dim);
        }
    }

    private function isInvalidIn($node)
    {
        return ($node instanceof Expr\Variable) === false
        && ($node instanceof Expr\ClassConstFetch) === false
        && ($node instanceof Expr\Cast) === false
        && ($node instanceof Expr\ConstFetch) === false
        && ($node instanceof Expr\StaticCall) === false
        && ($node instanceof Expr\PropertyFetch) === false
        && ($node instanceof BinaryOp\Minus) === false
        && ($node instanceof BinaryOp\Plus) === false
        && ($node instanceof BinaryOp\Mod) === false
        && ($node instanceof Scalar) === false
        && $node !== null;
    }

    public function arrayNeedToBeSplit(ArrayDimFetch $node)
    {
        $collected = array_reverse($this->findComplexArrayDimFetch($node));

        foreach ($collected as $rst) {
            if (is_array($rst) && $rst['splitTab'] === true) {
                return $collected;
            }
        }

        return false;
    }
}