<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Scalar;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpToZephir\converter\Manipulator\ArrayManipulator;

class AssignPrinter
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
     * @var ArrayManipulator
     */
    private $arrayManipulator = null;

    /**
     * @param Dispatcher       $dispatcher
     * @param Logger           $logger
     * @param ArrayManipulator $arrayManipulator
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ArrayManipulator $arrayManipulator
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->arrayManipulator = $arrayManipulator;
    }

    public static function getType()
    {
        return "pExpr_Assign";
    }

    /**
     * @param  Expr\Assign $node
     * @return string
     */
    public function convert(Expr\Assign $node)
    {
        $type           = 'Expr_Assign';
        $leftNode       = $node->var;
        $operatorString = ' = ';
        $rightNode      = $node->expr;

        list($precedence, $associativity) = $this->dispatcher->getPrecedenceMap($type);

        if ($rightNode instanceof Expr\Array_) {
            $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
            $collect = $this->dispatcher->pExpr_Array($rightNode, true);

            return implode(";\n", $collect['extracted'])."\n".
                'let '.$this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1)
                .$operatorString.' '.$collect['expr'];
        } elseif ($rightNode instanceof Expr\Ternary) {
            $collect = $this->dispatcher->pExpr_Ternary($rightNode, true);

            return implode(";\n", $collect['extracted'])."\n".
                   'let '.$this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1)
            .$operatorString.' '.$collect['expr'];
        } elseif ($node->var instanceof Expr\List_) {
            return $this->convertListStmtToAssign($node);
        } elseif ($leftNode instanceof Expr\ArrayDimFetch || $rightNode instanceof Expr\ArrayDimFetch) {
            return $this->arrayDimFetchCase($node, $leftNode, $rightNode, $operatorString, $precedence, $associativity);
        } elseif ($rightNode instanceof Expr\Assign) { // multiple assign
            $valueToAssign = ' = '.$this->dispatcher->p($this->findValueToAssign($rightNode));
            $vars = array($this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1));
            foreach ($this->findVarToAssign($rightNode) as $nodeAssigned) {
                $vars[] = $nodeAssigned;
            }

            $toReturn = '';

            foreach ($vars as $var) {
                $toReturn .= 'let '.$var.$valueToAssign.";\n";
            }

            return $toReturn;
        } elseif ($rightNode instanceof Variable || $rightNode instanceof Scalar || $rightNode instanceof Array_) {
            $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

            return 'let '.$this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1)
            .$operatorString
            .$this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1);
        } else {
            $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

            return 'let '.$this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1)
                   .$operatorString.' '.$this->dispatcher->p($rightNode);
        }
    }

    private function isSomething($rightNode)
    {
        return  $rightNode instanceof Variable ||
                $rightNode instanceof Scalar ||
                $rightNode instanceof Array_ ||
                $rightNode instanceof BinaryOp\Concat ||
                $rightNode instanceof BinaryOp\BooleanOr ||
                $rightNode instanceof BinaryOp\Minus ||
                $rightNode instanceof BinaryOp\Plus ||
                $rightNode instanceof BinaryOp\BitwiseOr ||
                $rightNode instanceof BinaryOp\BitwiseAnd ||
                $rightNode instanceof Expr\UnaryMinus ||
                $rightNode instanceof BinaryOp\Mul ||
                $rightNode instanceof Expr\StaticCall ||
                $rightNode instanceof Expr\FuncCall ||
                $rightNode instanceof Expr\ConstFetch ||
                $rightNode instanceof Expr\Clone_ ||
                $rightNode instanceof Expr\New_ ||
                $rightNode instanceof Expr\ClassConstFetch ||
                $rightNode instanceof Expr\Ternary ||
                $rightNode instanceof Expr\BooleanNot ||
                $rightNode instanceof Expr\Cast ||
                $rightNode instanceof Expr\MethodCall ||
                $rightNode instanceof Expr\Isset_ ||
                $rightNode instanceof Expr\Empty_ ||
                $rightNode instanceof Expr\Closure ||
                $rightNode instanceof Expr\ArrayDimFetch ||
                $rightNode instanceof Expr\Include_ ||
                $rightNode instanceof Expr\PropertyFetch;
    }

    private function findValueToAssign($rightNode)
    {
        if ($rightNode->expr instanceof Expr\Assign) {
            return $this->findValueToAssign($rightNode->expr);
        } else {
            return $rightNode->expr;
        }
    }

    private function convertListStmtToAssign($node)
    {
        $type = 'Expr_Assign';
        $leftNode = $node->var;
        $operatorString = ' = ';
        $rightNode = $node->expr;
        list($precedence, $associativity) = $this->dispatcher->getPrecedenceMap($type);
        $vars = array();
        $pList = array();
        foreach ($node->var->vars as $count => $var) {
            if (null === $var) {
                $pList[] = '';
            } else {
                $vars[] = $this->dispatcher->p($var);
                $pList[] = 'let '.$this->dispatcher->p($var).' = '.$this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1).'['.$count.'];';
            }
        }

        return 'var '.implode(", ", $vars).";\n".implode("\n", $pList);
    }

    private function findVarToAssign($rightNode, array $toAssign = array())
    {
        if ($rightNode->expr instanceof Expr\Assign) {
            $toAssign = $this->findVarToAssign($rightNode->expr);
        }
        $toAssign[] = $this->dispatcher->p($rightNode->var);

        return $toAssign;
    }

    private function arrayDimFetchCase($node, $leftNode, $rightNode, $operatorString, $precedence, $associativity)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
        $head = '';

        if ($leftNode instanceof ArrayDimFetch) {
            if (false === $splitedArray = $this->arrayManipulator->arrayNeedToBeSplit($leftNode)) {
                $leftString = $this->dispatcher->pPrec($leftNode, $precedence, $associativity, 1);
            } else {
                $result = $this->dispatcher->pExpr_ArrayDimFetch($leftNode, true);
                $head .= $result['head'];
                $leftString = $result['lastExpr'];
            }
        } else {
            $leftString = $this->dispatcher->pPrec($leftNode, $precedence, $associativity, -1);
        }

        if ($rightNode instanceof ArrayDimFetch) {
            if (false === $splitedArray = $this->arrayManipulator->arrayNeedToBeSplit($rightNode)) {
                $rightString = $this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1);
            } else {
                $result = $this->dispatcher->pExpr_ArrayDimFetch($rightNode, true);
                $head .= $result['head'];
                $rightString = $result['lastExpr'];
            }
        } elseif ($this->isSomething($rightNode)) {
            $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
            // @TODO add test case for each
            $rightString = $this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1);
        } else {
            $head .= $this->dispatcher->pPrec($rightNode, $precedence, $associativity, 1).";\n";
            $rightString = $this->dispatcher->p($rightNode->var);
        }

        return $head.'let '.$leftString.$operatorString.$rightString;
    }
}
