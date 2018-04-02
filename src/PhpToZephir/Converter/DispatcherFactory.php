<?php

namespace PhpToZephir\Converter;

class DispatcherFactory
{
    /**
     * @var \PhpToZephir\Converter\Dispatcher $dispatcher
     */
    private static $dispatcher;

    private function __construct() {}
    
    public static function getInstance()
    {
        if(static::$dispatcher !== null) {
            return static::$dispatcher;   
        }
        $dirName = __DIR__.'/Printer/';
        $directory = new \RecursiveDirectoryIterator($dirName);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
        $classes = new PrinterCollection(array());
        include 'SimplePrinter.php';

        foreach ($regex as $fileInfo) {
            $declaredClasses = get_declared_classes();
            include $fileInfo[0];

            $className = current(array_diff(get_declared_classes(), $declaredClasses));

            $classes->offsetSet($className::getType(), $className);
        }

        return static::$dispatcher = new Dispatcher(
            $classes,
            include __DIR__.'/PrecedenceMap.php'
        );
    }
}
