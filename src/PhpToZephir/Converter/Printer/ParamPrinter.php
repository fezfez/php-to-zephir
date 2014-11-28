<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class ParamPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pParam";
    }

    public function convert(Node\Param $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return ($node->type ? (is_string($node->type) ? $node->type : $this->dispatcher->p($node->type)).' ' : '')
             .($node->byRef ? '&' : '')
             .($node->variadic ? '... ' : '')
             .''.$node->name
             .($node->default ? ' = '.$this->dispatcher->p($node->default) : '').'';
    }
}
