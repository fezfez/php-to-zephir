<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class ModifiersPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return 'pModifiers';
    }

    /**
     * @param int $modifiers
     *
     * @return string
     */
    public function convert($modifiers)
    {
        return ($modifiers & Stmt\Class_::MODIFIER_PUBLIC    ? 'public '    : '')
              .($modifiers & Stmt\Class_::MODIFIER_PROTECTED ? 'protected ' : '')
              .($modifiers & Stmt\Class_::MODIFIER_PRIVATE   ? 'protected ' : '') // due to #issues/251
.($modifiers & Stmt\Class_::MODIFIER_STATIC    ? 'static '    : '')
              .($modifiers & Stmt\Class_::MODIFIER_ABSTRACT  ? 'abstract '  : '')
              .($modifiers & Stmt\Class_::MODIFIER_FINAL     ? 'final '     : '');
    }
}
