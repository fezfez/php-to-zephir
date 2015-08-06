<?php

namespace PhpToZephir\Converter\Printer\Stmt\TraitUseAdaptation;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class AliasPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pStmt_TraitUseAdaptation_Alias';
    }

    public function convert(Stmt\TraitUseAdaptation\Alias $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());
    }
}
