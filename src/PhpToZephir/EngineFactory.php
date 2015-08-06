<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;
use PhpToZephir\Converter\ConverterFactory;

class EngineFactory
{
    /**
     * @return Engine
     */
    public static function getInstance()
    {
        return new Engine(
            new Parser(new Emulative()),
            ConverterFactory::getInstance(),
            ClassCollectorFactory::getInstance()
        );
    }
}
