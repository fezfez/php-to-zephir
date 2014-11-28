<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ElseIfPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_ElseIf";
    }

    public function convert(Stmt\ElseIf_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return ' elseif '.$this->dispatcher->p($node->cond).' {'
             .$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
