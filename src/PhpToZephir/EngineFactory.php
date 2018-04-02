<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;
use PhpToZephir\Converter\ConverterFactory;

class EngineFactory
{
    /**
    * @var Engine $engine
    */
    private static $engine;
    
    private function __construct() {}
    
    /**
     * @return Engine
     */
    public static function getInstance()
    {
        if(static::$engine !== null) {
            return static::$engine;
        }
        return static::$engine = new Engine(
            new Parser(new Emulative()),
            ConverterFactory::getInstance(),
            ClassCollectorFactory::getInstance()
        );
    }
}
