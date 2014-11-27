<?php

namespace PhpToZephir\Converter;

use PhpToZephir\TypeFinder;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\NodeFetcher;
use PhpToZephir\converter\Manipulator\AssignManipulator;
use PhpToZephir\converter\Manipulator\ArrayManipulator;
use PhpToZephir\Logger;

class DispatcherFactory
{
    /**
     * @var \PhpToZephir\Converter\Dispatcher
     */
    private static $instance = null;

    /**
     * @param Logger $logger
     * @return \PhpToZephir\Converter\Dispatcher
     */
    public static function getInstance(Logger $logger)
    {
        if (self::$instance === null) {
            self::$instance = self::createInstance($logger);
        }

        return self::$instance;
    }

    /**
     * @param Logger $logger
     * @return \PhpToZephir\Converter\Dispatcher
     */
    private static function createInstance(Logger $logger)
    {
        $dirName   = __DIR__ . '/Printer/';
        $Directory = new \RecursiveDirectoryIterator($dirName);
        $Iterator  = new \RecursiveIteratorIterator($Directory);
        $Regex     = new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
        $classes   = new PrinterCollection(array());

        foreach ($Regex as $fez) {
            $tmp = get_declared_classes();
            include $fez[0];

            $className = current(array_diff(get_declared_classes(), $tmp));

            $classes->offsetSet($className::getType(), $className);
        }

        return new Dispatcher(
            $classes,
            $logger,
            include __DIR__ . '/PrecedenceMap.php'
        );
    }
}