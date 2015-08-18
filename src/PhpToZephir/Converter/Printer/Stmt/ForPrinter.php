<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar;
use PhpParser\Node;

class ForPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_For';
    }

    /**
     * @param Stmt\For_ $node
     *
     * @return string
     */
    public function convert(Stmt\For_ $node)
    {
        if (is_array($node->cond) && count($node->cond) > 1) {
            throw new \Exception(sprintf('Cannot convert %s ', $this->dispatcher->pCommaSeparated($node->cond)));
        }

        if (count($node->cond) === 0) {
            return (!empty($node->init) ? $this->dispatcher->pStmts($node->init)."\n" : '')
               .'loop'
              .' {'.$this->dispatcher->pStmts($node->stmts)."\n".$this->dispatcher->pStmts($node->loop)."\n".'}';
        } elseif ($node->cond[0] instanceof BinaryOp) {
            $node = $this->findIteratorVar($node);

            return $this->printVars($node) . 'for '
                 .$this->dispatcher->p($node->init[0]->var).' in '.(!empty($node->cond) ? '' : '')
                 .'range('.$this->dispatcher->p($node->cond[0]->left).', '.$this->dispatcher->p($node->cond[0]->right).')'
                 .' {'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
        } elseif (count($node->cond) === 1 && $node->cond[0] instanceof Node\Expr) {
        	$ifNode = new Stmt\If_($node->cond[0], array('stmts' => array(new Node\Stmt\Break_())));
        	return (!empty($node->init) ? $this->dispatcher->pStmts($node->init)."\n" : '')
        	.'loop'
        			.' {'.$this->dispatcher->p($ifNode)."\n".$this->dispatcher->pStmts($node->stmts)."\n".$this->dispatcher->pStmts($node->loop)."\n".'}';
        } else {
        	throw new \Exception(sprintf('Cannot convert %s ', $this->dispatcher->pCommaSeparated($node->cond)));
        }
    }
    
    private function printVars(Stmt\For_ $node)
    {
    	$initPrint = '';
    	foreach ($node->init as $init) {
    		$initPrint .= $this->dispatcher->p($init) . ";\n";
    	}
    	
    	return $initPrint;
    }

    /**
     * @param Stmt\For_ $node
     *
     * @return \Stmt\For_
     */
    private function findIteratorVar(Stmt\For_ $node)
    {
        $varName = $node->init[0]->var;
        $varValue = $node->init[0]->expr;

        if ($node->cond[0]->left instanceof Variable && $node->cond[0]->left->name === $varName->name) {
            $node->cond[0]->left = $varValue;
        } elseif ($node->cond[0]->right instanceof Variable && $node->cond[0]->right->name === $varName->name) {
            $node->cond[0]->right = $varValue;
        }
        
        if ($node->cond[0] instanceof BinaryOp\Smaller && $node->cond[0]->right instanceof Scalar\LNumber) {
        	--$node->cond[0]->right->value;
        }

        return $node;
    }
}
