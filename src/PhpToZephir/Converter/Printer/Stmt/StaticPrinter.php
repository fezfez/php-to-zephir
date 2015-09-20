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
            $this->logger->logIncompatibility(
                'Static var',
                'Static var does not exist in Zephir see #941',
                $var,
                $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
            );
            /* @var $var \PhpParser\Node\Stmt\StaticVar */
            if (!empty($var->default)) {
                $vars[] = new Expr\Assign(new Expr\Variable($var->name), $var->default);
            }
        }

        return $this->dispatcher->pStmts($vars);
    }
}
