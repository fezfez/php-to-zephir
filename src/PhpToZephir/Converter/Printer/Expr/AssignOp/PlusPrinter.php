<?php

namespace PhpToZephir\Converter\Printer\Expr\AssignOp;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpToZephir\Converter\SimplePrinter;
use PhpParser\Node\Expr\Array_;

class PlusPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_AssignOp_Plus';
    }

    public function convert(AssignOp\Plus $node)
    {
    	if ($node->expr instanceof Array_) {
    		return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Plus', $node->var, ' = this->array_plus(' .$this->dispatcher->p($node->var) . ', ', $node->expr) . ')';
    	} else {
        	return 'let '.$this->dispatcher->pInfixOp('Expr_AssignOp_Plus', $node->var, ' += ', $node->expr);
    	}
    }
}
