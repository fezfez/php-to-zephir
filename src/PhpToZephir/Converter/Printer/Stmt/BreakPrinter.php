<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class BreakPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Break";
    }

    public function convert(Stmt\Break_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return 'break'.($node->num !== null ? ' '.$this->dispatcher->p($node->num) : '').';';
    }
}
