<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class StaticPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Static';
    }

    public function convert(Stmt\Static_ $node)
    {
        $vars = array();

        foreach ($node->vars as $var) {
            $this->logger->logNode('Static var does not exist in Zepihr', $var);
            /* @var $var \PhpParser\Node\Stmt\StaticVar */
            $vars[] = new Expr\Assign(new Expr\Variable($var->name), $var->default);
        }

        return $this->dispatcher->pStmts($vars);
    }
}
