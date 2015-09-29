<?php

namespace PhpToZephir\Converter\Manipulator;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Scalar;
use PhpToZephir\Logger;

class ArrayManipulator
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
     * @param Dispatcher $dispatcher
     * @param Logger     $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * @param Expr\ArrayDimFetch $node
     */
    private function findComplexArrayDimFetch($node, $collected = array())
    {
        if ($this->isInvalidInArrayDimFetch($node) === true) {
            if ($node->dim instanceof Expr\FuncCall) {
                $this->logger->logIncompatibility(
                    'ArrayFetchDim',
                    'supported funccall in array',
                    $node,
                    $this->dispatcher->getMetadata()->getClass()
                );
            } else {
                $collected[] = array(
                    'expr' => $this->dispatcher->p($node->dim).";\n",
                    'splitTab' => true,
                    'var' => $this->dispatcher->p($node->dim->var),
                );
            }
        } else {
            if ($node->dim === null) {
                $collected[] = array('expr' => $this->dispatcher->p($node->var), 'splitTab' => false);
            } else {
                $collected[] = array('expr' => $this->dispatcher->p($node->dim), 'splitTab' => false);
            }
        }

        if ($node->var instanceof Expr\ArrayDimFetch) {
            $collected = $this->findComplexArrayDimFetch($node->var, $collected);
        } else {
            $collected[] = $node->var;
        }

        return $collected;
    }

    /**
     * @param unknown $node
     *
     * @return bool
     */
    private function isInvalidInArrayDimFetch($node)
    {
        if (property_exists($node, 'dim') === false) {
            return $this->isInvalidIn($node);
        } elseif ($node->dim instanceof BinaryOp\Concat) {
            return $this->isInvalidInArrayDimFetch($node->dim->left)
            && $this->isInvalidInArrayDimFetch($node->dim->right);
        } else {
            return $this->isInvalidIn($node->dim);
        }
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return bool
     */
    private function isInvalidIn($node)
    {
        return ($node instanceof Expr\Variable) === false
        && ($node instanceof Expr\ClassConstFetch) === false
        && ($node instanceof Expr\Cast) === false
        && ($node instanceof Expr\ConstFetch) === false
        && ($node instanceof Expr\StaticCall) === false
        && ($node instanceof Expr\PropertyFetch) === false
        && ($node instanceof Expr\ArrayDimFetch) === false
        && ($node instanceof Expr\Assign) === false
        && ($node instanceof BinaryOp\Minus) === false
        && ($node instanceof BinaryOp\Plus) === false
        && ($node instanceof BinaryOp\Mod) === false
        && ($node instanceof Scalar) === false
        && $node !== null;
    }

    /**
     * @param Expr\ArrayDimFetch $node
     *
     * @return array|bool
     */
    public function arrayNeedToBeSplit(Expr\ArrayDimFetch $node)
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
