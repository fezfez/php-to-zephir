<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ForeachPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Foreach";
    }

    public function convert(Stmt\Foreach_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return 'for '.(null !== $node->keyVar ? $this->dispatcher->p($node->keyVar).', ' : '').$this->dispatcher->p($node->valueVar).
               ' in '.$this->dispatcher->p($node->expr).' {'.
               $this->dispatcher->pStmts($node->stmts)."\n".'}';
    }
}
