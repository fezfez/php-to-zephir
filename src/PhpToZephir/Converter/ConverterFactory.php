<?php

namespace PhpToZephir\Converter;

use PhpToZephir\Logger;
use PhpToZephir\NodeFetcher;

class ConverterFactory
{
    /**
     * @param Logger $logger
     * @return \PhpToZephir\Converter\Converter
     */
    public static function getInstance(Logger $logger)
    {
        return new Converter(DispatcherFactory::getInstance($logger), $logger, new NodeFetcher());
    }
}
