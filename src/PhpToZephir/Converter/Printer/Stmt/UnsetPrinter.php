<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;
use PhpParser\Node\Expr;

class UnsetPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_Unset';
    }

    /**
     * @param Stmt\Unset_ $node
     *
     * @return string
     */
    public function convert(Stmt\Unset_ $node)
    {
        $unset = '';
        foreach ($node->vars as $var) {
        	if ($var instanceof Expr\Variable) {
        		$unset .= 'let '.$this->dispatcher->p($var).' = null;'."\n";
        	} else {
            	$unset .= 'unset '.$this->dispatcher->p($var).';'."\n";
        	}
        }

        return $unset;
    }
}
