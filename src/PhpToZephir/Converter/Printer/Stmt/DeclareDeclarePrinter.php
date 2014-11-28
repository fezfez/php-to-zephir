<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class DeclareDeclarePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_DeclareDeclare";
    }

    public function convert(Stmt\DeclareDeclare $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $node->key.' = '.$this->dispatcher->p($node->value);
    }
}
