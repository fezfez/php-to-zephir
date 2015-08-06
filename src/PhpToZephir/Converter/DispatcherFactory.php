<?php

namespace PhpToZephir\Converter;

class DispatcherFactory
{
    /**
     * @var \PhpToZephir\Converter\Dispatcher
     */
    private static $instance = null;

    /**
     * @return \PhpToZephir\Converter\Dispatcher
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = self::createInstance();
        }

        return self::$instance;
    }

    /**
     * @return \PhpToZephir\Converter\Dispatcher
     */
    private static function createInstance()
    {
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

        return new Dispatcher(
            $classes,
            include __DIR__.'/PrecedenceMap.php'
        );
    }
}
