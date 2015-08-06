<?php

namespace PhpToZephir\Converter;

use PhpToZephir\NodeFetcher;

class ConverterFactory
{
    /**
     * @return \PhpToZephir\Converter\Converter
     */
    public static function getInstance()
    {
        return new Converter(DispatcherFactory::getInstance(), new NodeFetcher());
    }
}
