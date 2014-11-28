<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;
use PhpToZephir\Converter\ConverterFactory;

class EngineFactory
{
    /**
     * @param  Logger              $logger
     * @return \PhpToZephir\Engine
     */
    public static function getInstance(Logger $logger)
    {
        return new Engine(
            new Parser(new Emulative()),
            ConverterFactory::getInstance($logger),
            ClassCollectorFactory::getInstance($logger),
            $logger
        );
    }
}
