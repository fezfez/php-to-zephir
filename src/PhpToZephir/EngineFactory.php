<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;

class EngineFactory
{
    public static function getInstance()
    {
        return new Engine(
            new Parser(new Emulative()),
            new Converter(new TypeFinder())
        );
    }
}