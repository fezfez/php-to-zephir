<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class CatchPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Catch";
    }

    public function convert(Stmt\Catch_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return ' catch '.$this->dispatcher->p($node->type).', '.$node->var.' {'
             .$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
