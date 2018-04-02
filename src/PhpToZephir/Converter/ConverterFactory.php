<?php

namespace PhpToZephir\Converter;

use PhpToZephir\NodeFetcher;

class ConverterFactory
{
    static $converter;
    
    /**
     * @return \PhpToZephir\Converter\Converter
     */
    public static function getInstance()
    {
        if(static::$converter !== null) {
            return static::$converter;   
        }
        return static::$converter = new Converter(DispatcherFactory::getInstance(), new NodeFetcher());
    }
}
