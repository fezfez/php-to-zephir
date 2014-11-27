<?php

namespace PhpToZephir\Converter;

use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\NodeFetcher;

class ClassInformationFactory
{
    /**
     * @return \PhpToZephir\Converter\ClassInformation
     */
    public static function getInstance()
    {
        return new ClassInformation(
            new ReservedWordReplacer(),
            new NodeFetcher()
        );
    }
}
