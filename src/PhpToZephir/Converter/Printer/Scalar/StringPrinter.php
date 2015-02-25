<?php

namespace PhpToZephir\Converter\Printer\Scalar;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\String;
use PhpToZephir\Converter\SimplePrinter;

class StringPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pScalar_String";
    }

    /**
     * @param Scalar\String $node
     *
     * @return string
     */
    public function convert(Scalar\String $node)
    {
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
