<?php

namespace PhpToZephir;

class ClassCollectorFactory
{
    /**
    * @var ClassCollector $collector;
    */
    private static $collector;
    
    private function __construct() {}
    
    /**
     * @return \PhpToZephir\ClassCollector
     */
    public static function getInstance()
    {
        if(static::$collector !== null) {
            return static::$collector;   
        }
        return static::$collector = new ClassCollector(
            new NodeFetcher(),
            new ReservedWordReplacer()
        );
    }
}
