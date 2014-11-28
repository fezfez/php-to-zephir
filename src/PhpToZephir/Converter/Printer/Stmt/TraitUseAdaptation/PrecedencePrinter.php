<?php

namespace PhpToZephir\Converter\Printer\Stmt\TraitUseAdaptation;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class PrecedencePrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_TraitUseAdaptation_Precedence";
    }

    public function convert(Stmt\TraitUseAdaptation\Precedence $node)
    {
        return $this->dispatcher->p($node->trait).'::'.$node->method
             .' insteadof '.$this->dispatcher->pCommaSeparated($node->insteadof).';';
    }
}
