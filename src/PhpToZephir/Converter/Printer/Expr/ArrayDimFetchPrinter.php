<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
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
        $this->logger = $logger;
        $this->arrayManipulator = $arrayManipulator;
    }

    public static function getType()
    {
        return 'pExpr_ArrayDimFetch';
    }

    public function convert(Expr\ArrayDimFetch $node, $returnAsArray = false, $isRightNodeInAssign = false)
    {
        $collected = $this->arrayManipulator->arrayNeedToBeSplit($node);

        if ($collected !== false) {
            return $this->splitArray($collected, $returnAsArray, $isRightNodeInAssign);
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
     * @param bool $returnAsArray
     */
    private function splitArray(array $collected, $returnAsArray, $isRightNodeInAssign)
    {
        $var = $collected[0];
        unset($collected[0]);
        $lastExpr = null;
        $createAsTmp = array();


        $head = "";
        $lastSplitTable = true;
        foreach ($collected as $expr) {
            if ($expr['splitTab'] === true) {
                if ($isRightNodeInAssign === false) {
                    $head .= $expr['expr'];
                    $createAsTmp = $this->addAsTmp($createAsTmp, $expr['var'] );
                    $head .= 'let tmp' . ucfirst($expr['var']) . $createAsTmp[$expr['var']] .' = ' . $expr['var'] . ";\n";
                } else {
                    $head .= $expr['expr'];
                }
                if ($expr !== end($collected)) {
                    $head .= 'let tmpArray = ';
                    $head .= $this->dispatcher->p($var).'['.$expr['var'].']';
                } elseif ($isRightNodeInAssign === false) {
                    $lastExpr = $this->dispatcher->p($var).'[tmp'.$expr['var'].']';
                } else {
                    $lastExpr = $this->dispatcher->p($var).'['.$expr['var'].']';
                }

                $lastSplitTable = true;
            } else {
                if ($lastSplitTable === true) {
                    if ($expr === end($collected)) {
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
    
    private function addAsTmp($createAsTmp, $varname)
    {
        if (isset($createAsTmp[$varname])) {
            $createAsTmp[$varname]++;
        } else {
            $createAsTmp[$varname] = 1;
        }
        
        return $createAsTmp;
    }
}
