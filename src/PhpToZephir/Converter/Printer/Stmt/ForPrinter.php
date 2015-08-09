<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Variable;

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
        $transformAsLoop = false;

        if (is_array($node->init) && count($node->init) > 1) {
            throw new \Exception(sprintf('Cannot convert %s ', $this->dispatcher->pCommaSeparated($node->init)));
        }

        if (is_array($node->cond) && count($node->cond) > 1) {
            throw new \Exception(sprintf('Cannot convert %s ', $this->dispatcher->pCommaSeparated($node->cond)));
        }

        if (count($node->cond) === 0) {
            return (!empty($node->init) ? $this->dispatcher->pStmts($node->init)."\n" : '')
               .'loop'
              .' {'.$this->dispatcher->pStmts($node->stmts)."\n".$this->dispatcher->pStmts($node->loop)."\n".'}';
        } else {
            $node = $this->findIteratorVar($node);

            return 'for '
                 .$this->dispatcher->p($node->init[0]->var).' in '.(!empty($node->cond) ? '' : '')
                 .'range('.$this->dispatcher->p($node->cond[0]->left).', '.$this->dispatcher->p($node->cond[0]->right).')'
                 .' {'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
        }
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

        return $node;
    }
}
