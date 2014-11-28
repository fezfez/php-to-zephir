<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Scalar;
use PhpToZephir\Converter\SimplePrinter;

class DNumberPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_DNumber";
    }

    public function convert(Scalar\DNumber $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return $node->value;
    }
}
