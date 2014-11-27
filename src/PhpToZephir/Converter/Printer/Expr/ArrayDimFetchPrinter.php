<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use phpDocumentor\Reflection\DocBlock;
use PhpToZephir\converter\Manipulator\ArrayManipulator;

class ArrayDimFetchPrinter
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
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     * @param ArrayManipulator $arrayManipulator
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ArrayManipulator $arrayManipulator)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->arrayManipulator = $arrayManipulator;
    }

    public static function getType()
    {
        return "pExpr_ArrayDimFetch";
    }

    public function convert(Expr\ArrayDimFetch $node, $returnAsArray = false) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $collected = $this->arrayManipulator->arrayNeedToBeSplit($node);

        if($collected !== false) {

            $var = $collected[0];
            unset($collected[0]);
            $lastExpr = null;


            $head = "var tmpArray;\n";
            $lastSplitTable = true;
            foreach ($collected as $expr) {
                if ($expr['splitTab'] === true) {
                    $head .= $expr['expr'];
                    if ($expr !== end($collected)) {
                        $head .= 'let tmpArray = ';
                        $head .= $this->dispatcher->p($var) . '[' . $expr['var'] . ']';
                    } else {
                        $lastExpr = $this->dispatcher->p($var) . '[' . $expr['var'] . ']';
                    }

                    $lastSplitTable = true;
                } else {
                    if ($lastSplitTable === true) {
                        if ($expr !== end($collected)) {
                            $head .= 'let tmpArray = ';
                            $head .= $this->dispatcher->p($var) . '[' . $expr['expr'] . ']';
                        } else {
                            $lastExpr = $this->dispatcher->p($var) . '[' . $expr['expr'] . ']';
                        }
                    }
                }

                if ($expr !== end($collected)) {
                    $head .= ';' . "\n";
                }
            }

            if ($returnAsArray === true) {
                return array(
                    'head' => $head,
                    'lastExpr' => $lastExpr
                );
            } else {
                return $head;
            }
        } else {
            $result = $this->dispatcher->pVarOrNewExpr($node->var)
                 . '[' . (null !== $node->dim ? $this->dispatcher->p($node->dim) : '') . ']';

            if ($returnAsArray === true) {
                return array(
                    'head' => '',
                    'lastExpr' => $result
                );
            } else {
                return $result;
            }
        }
    }
}
