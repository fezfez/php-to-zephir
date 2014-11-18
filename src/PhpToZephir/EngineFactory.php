<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;

class EngineFactory
{
    public static function getInstance(Logger $logger)
    {
        return new Engine(
            new Parser(new Emulative()),
            new Converter(new TypeFinder(), $logger),
            new ClassCollector($logger)
        );
    }
}