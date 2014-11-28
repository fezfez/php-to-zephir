<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Scalar;
use PhpToZephir\Converter\SimplePrinter;

class EncapsedPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_Encapsed";
    }

    public function convert(Scalar\Encapsed $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return '"'.$this->dispatcher->pEncapsList($node->parts, '"').'"';
    }
}
