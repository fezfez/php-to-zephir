<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\SimplePrinter;

class EncapsListPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pEncapsList';
    }

    public function convert(array $encapsList, $quote)
    {
        $return = '';
        foreach ($encapsList as $element) {
            if (is_string($element)) {
                $return .= addcslashes($element, "\n\r\t\f\v$".$quote.'\\');
            } else {
                $return .= '{'.$this->dispatcher->p($element).'}';
            }
        }

        return $return;
    }
}
