<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ThrowPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Throw";
    }

    public function convert(Stmt\Throw_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return 'throw '.$this->dispatcher->p($node->expr).';';
    }
}
