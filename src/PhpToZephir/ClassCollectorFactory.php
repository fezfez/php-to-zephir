<?php

namespace PhpToZephir;

class ClassCollectorFactory
{
    /**
     * @return \PhpToZephir\ClassCollector
     */
    public static function getInstance()
    {
        return new ClassCollector(
            new NodeFetcher(),
            new ReservedWordReplacer()
        );
    }
}
