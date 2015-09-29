<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class FunctionPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_Function';
    }

    public function convert(Stmt\Function_ $node)
    {
        if ($node->byRef) {
            $this->logger->logIncompatibility(
                'reference',
                'Reference not supported',
                $node,
                $this->dispatcher->getMetadata()->getClass()
            );
        }
        
        return 'function '.$node->name
             .'('.$this->dispatcher->pCommaSeparated($node->params).')'
             ."\n".'{'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
