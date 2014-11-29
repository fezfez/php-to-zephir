<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpToZephir\Converter\Manipulator\ArrayManipulator;

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
     * @param Dispatcher       $dispatcher
     * @param Logger           $logger
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

    public function convert(Expr\ArrayDimFetch $node, $returnAsArray = false)
    {
        $collected = $this->arrayManipulator->arrayNeedToBeSplit($node);

        if ($node->var instanceof Expr\StaticPropertyFetch) {
            if ($node->var->class->parts[0] === 'self') {
                $node->var = new Expr\PropertyFetch(new Expr\Variable('this'), $node->var->name);
                $this->logger->logNode("Change StaticProperty into PropertyFetch due to #188", $node);
            } else {
                $this->logger->logNode("StaticProperty on array not supported, see #188", $node);
            }
        }

        if ($collected !== false) {
            return $this->splitArray($collected, $returnAsArray);
        } else {
            $result = $this->dispatcher->pVarOrNewExpr($node->var)
                 .'['.(null !== $node->dim ? $this->dispatcher->p($node->dim) : '').']';

            if ($returnAsArray === true) {
                return array(
                    'head' => '',
                    'lastExpr' => $result,
                );
            } else {
                return $result;
            }
        }
    }

    /**
     * @param boolean $returnAsArray
     */
    private function splitArray(array $collected, $returnAsArray)
    {
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
                    $head .= $this->dispatcher->p($var).'['.$expr['var'].']';
                } else {
                    $lastExpr = $this->dispatcher->p($var).'['.$expr['var'].']';
                }

                $lastSplitTable = true;
            } else {
                if ($lastSplitTable === true) {
                    if ($expr !== end($collected)) {
                        $head .= 'let tmpArray = ';
                        $head .= $this->dispatcher->p($var).'['.$expr['expr'].']';
                    } else {
                        $lastExpr = $this->dispatcher->p($var).'['.$expr['expr'].']';
                    }
                }
            }

            if ($expr !== end($collected)) {
                $head .= ';'."\n";
            }
        }

        if ($returnAsArray === true) {
            return array(
                'head' => $head,
                'lastExpr' => $lastExpr,
            );
        } else {
            return $head.$lastExpr;
        }
    }
}
