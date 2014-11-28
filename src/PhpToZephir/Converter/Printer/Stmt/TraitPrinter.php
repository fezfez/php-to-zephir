<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class TraitPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Trait";
    }

    public function convert(Stmt\Trait_ $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());
    }
}
