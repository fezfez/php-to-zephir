<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\String;
use PhpToZephir\Converter\SimplePrinter;

class StringPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pScalar_String";
    }

    public function convert(Scalar\String $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        return '"'.$this->pNoIndent(addcslashes($node->value, '\"\\')).'"';
    }

    /**
     * Signals the pretty printer that a string shall not be indented.
     *
     * @param string $string Not to be indented string
     *
     * @return string String marked with $this->noIndentToken's.
     */
    private function pNoIndent($string)
    {
        return str_replace("\n", "\n".Dispatcher::noIndentToken, $string);
    }
}
