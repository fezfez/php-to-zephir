<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class TraitUsePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_TraitUse";
    }

    public function convert(Stmt\TraitUse $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());
    }
}
