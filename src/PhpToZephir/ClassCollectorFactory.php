<?php

namespace PhpToZephir;

class ClassCollectorFactory
{
    /**
     * @param Logger $logger
     *
     * @return \PhpToZephir\ClassCollector
     */
    public static function getInstance(Logger $logger)
    {
        return new ClassCollector(
            $logger,
            new NodeFetcher(),
            new ReservedWordReplacer()
        );
    }
}
