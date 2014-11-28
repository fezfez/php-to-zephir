<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ContinuePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Continue";
    }

    public function convert(Stmt\Continue_ $node)
    {
        if ($node->num !== null) {
            $this->logger->logNode('"continue $number;" no supported in zephir', $node, $this->dispatcher->getMetadata()->getClass());
        }

        return 'continue;';
    }
}
