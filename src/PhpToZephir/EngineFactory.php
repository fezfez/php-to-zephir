<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;
use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Converter\DispatcherFactory;
use PhpToZephir\Converter\ConverterFactory;

class EngineFactory
{
    public static function getInstance(Logger $logger)
    {
        $reservedWordReplacer = new ReservedWordReplacer();
        return new Engine(
            new Parser(new Emulative()),
            ConverterFactory::getInstance($logger),
            ClassCollectorFactory::getInstance($logger),
            $logger
        );
    }
}