<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;

class EngineFactory
{
    public static function getInstance(Logger $logger)
    {
        $reservedWordReplacer = new ReservedWordReplacer();
        return new Engine(
            new Parser(new Emulative()),
            new Converter(new TypeFinder($reservedWordReplacer), $logger, $reservedWordReplacer),
            new ClassCollector($logger),
            $logger
        );
    }
}