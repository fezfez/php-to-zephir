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
     * @var array
     */
    private static $createdVars = array();

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
    
    public static function resetCreatedVars()
    {
        self::$createdVars = array();
    }

    public static function getType()
    {
        return 'pExpr_ArrayDimFetch';
    }

    public function convert(Expr\ArrayDimFetch $node, $returnAsArray = false)
    {
        $collected = $this->arrayManipulator->arrayNeedToBeSplit($node);

        if ($collected !== false) {
            return $this->splitArray($collected, $returnAsArray);
        }

        $result = $this->dispatcher->pVarOrNewExpr($node->var)
             .'['.(null !== $node->dim ? $this->dispatcher->p($node->dim) : '').']';

        if ($returnAsArray === true) {
            return array(
                'head' => '',
                'lastExpr' => $result,
                'vars' => array()
            );
        }

        return $result;
    }

    /**
     * @param bool $returnAsArray
     */
    private function splitArray(array $collected, $returnAsArray)
    {
        $var         = $collected[0];
        $lastExpr    = $this->dispatcher->p($var);
        $createAsTmp = array();
        $head        = array();
        $vars        = array();

        unset($collected[0]);

        foreach ($collected as $expr) {
            if ($expr['splitTab'] === true) {
                $createAsTmp = $this->addAsTmp($createAsTmp, $expr['var'] );
                $tmpVarName  = 'tmp' . ucfirst($expr['var']) . $createAsTmp[$expr['var']];
                $vars[]      = $tmpVarName;
                $head[]      = $expr['expr'];
                $head[]      = 'let ' . $tmpVarName . ' = ' . $expr['var'] . ";\n";
                $lastExpr .= '[' . $tmpVarName .']';
            } else {
                $lastExpr .= '['.$expr['expr'].']';
            }
        }

        if ($returnAsArray === true) {
            return array(
                'head' => !empty($head) ? "\n" . implode("", $head) . "\n" : "",
                'lastExpr' => $lastExpr,
                'vars' => $vars
            );
        }

        return (!empty($head) ? "\n" . implode("", $head) . "\n" : "").$lastExpr;
    }
    
    private function addAsTmp(array $createAsTmp, $varname)
    {
        if (!isset(self::$createdVars[$this->dispatcher->getLastMethod()])) {
            self::$createdVars[$this->dispatcher->getLastMethod()] = array();
        }

        if (isset(self::$createdVars[$this->dispatcher->getLastMethod()][$varname])) {
            self::$createdVars[$this->dispatcher->getLastMethod()][$varname]++;
            $createAsTmp[$varname] = self::$createdVars[$this->dispatcher->getLastMethod()][$varname];
        } else {
            self::$createdVars[$this->dispatcher->getLastMethod()][$varname] = 1;
            $createAsTmp[$varname] = 1;
        }

        return $createAsTmp;
    }
}
