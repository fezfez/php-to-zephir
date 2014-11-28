<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class UnsetPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Unset";
    }

    public function convert(Stmt\Unset_ $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
        $unset = '';
        foreach ($node->vars as $var) {
            $unset .= 'unset('.$this->dispatcher->p($var).');'."\n";
        }

        return $unset;
    }
}
