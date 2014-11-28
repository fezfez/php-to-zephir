<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ElsePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Else";
    }

    public function convert(Stmt\Else_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return ' else {'.$this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
