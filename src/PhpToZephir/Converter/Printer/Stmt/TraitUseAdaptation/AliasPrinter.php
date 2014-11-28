<?php

namespace PhpToZephir\Converter\Printer\Stmt\TraitUseAdaptation;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class AliasPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_TraitUseAdaptation_Alias";
    }

    public function convert(Stmt\TraitUseAdaptation\Alias $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());
    }
}
