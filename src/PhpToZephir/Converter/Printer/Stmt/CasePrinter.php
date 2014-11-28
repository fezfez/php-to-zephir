<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class CasePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Case";
    }

    public function convert(Stmt\Case_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return (null !== $node->cond ? 'case '.$this->dispatcher->p($node->cond) : 'default').':'
             .$this->dispatcher->pStmts($node->stmts);
    }
}
