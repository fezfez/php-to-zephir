<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class SwitchPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Switch";
    }

    public function convert(Stmt\Switch_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $transformToIf = false;
        foreach ($node->cases as $case) {
            if (($case->cond instanceof \PhpParser\Node\Scalar\String) === false && $case->cond !== null) {
                $transformToIf = true;
            }
        }

        if ($transformToIf === true) {
            return $this->convertSwitchToIfelse($node);
        } else {
            return 'switch ('.$this->dispatcher->p($node->cond).') {'
             .$this->dispatcher->pStmts($node->cases)."\n".'}';
        }
    }

    private function removeBreakStmt($case)
    {
        if (is_array($case->stmts) && !empty($case->stmts)) {
            $key = array_keys($case->stmts);
            $breakStmt = $case->stmts[end($key)];
            if ($breakStmt instanceof \PhpParser\Node\Stmt\Break_) {
                unset($case->stmts[end($key)]);
            }
        }

        return $case;
    }

    private function convertSwitchToIfelse(Stmt\Switch_ $node)
    {
        $stmt = array(
            'else' => null,
            'elseifs' => array(),
        );
        $if = null;
        $ifDefined = false;
        $left = null;
        foreach ($node->cases as $case) {
            $case = $this->removeBreakStmt($case);
            if (end($node->cases) === $case) {
                $stmt['else'] = new \PhpParser\Node\Stmt\Else_($case->stmts);
            } else {
                if (empty($case->stmts)) { // concatene empty statement
                    if ($left !== null) {
                        $left = new BinaryOp\BooleanOr($left, $case->cond);
                    } else {
                        $left = $case->cond;
                    }
                } elseif ($ifDefined === false) {
                    if ($left !== null) {
                        $lastLeft = new BinaryOp\BooleanOr($left, $case->cond);
                        $if = new \PhpParser\Node\Stmt\If_($lastLeft, array('stmts' => $case->stmts));
                        $left = null;
                    } else {
                        $if = new \PhpParser\Node\Stmt\If_($case->cond, array('stmts' => $case->stmts));
                    }
                    $ifDefined = true;
                } else {
                    if ($left !== null) {
                        $lastLeft = new BinaryOp\BooleanOr($left, $case->cond);
                        $stmt['elseifs'][] = new \PhpParser\Node\Stmt\Elseif_($lastLeft, $case->stmts);
                        $left = null;
                    } else {
                        $stmt['elseifs'][] = new \PhpParser\Node\Stmt\Elseif_($case->cond, $case->stmts);
                    }
                }
            }
        }
        $elseifs = array_reverse($stmt['elseifs']);
        $if = new \PhpParser\Node\Stmt\If_($if->cond, array(
            'stmts' => $if->stmts,
            'elseifs' => $elseifs,
            'else' => $stmt['else'],
        ));

        return $this->dispatcher->pStmt_If($if);
    }
}
