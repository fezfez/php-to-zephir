<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Name;
use PhpToZephir\Converter\Manipulator\ClassManipulator;

class NamePrinter
{
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;
    /**
     * @var Logger
     */
    private $logger = null;
    /**
     * @var ClassManipulator
     */
    private $classManipulator = null;

    /**
     * @param Dispatcher       $dispatcher
     * @param Logger           $logger
     * @param ClassManipulator $classManipulator
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ClassManipulator $classManipulator)
    {
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
        $this->classManipulator = $classManipulator;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return "pName";
    }

    /**
     * @param  Name     $node
     * @return Ambigous <string, unknown>
     */
    public function convert(Name $node)
    {
        return $this->classManipulator->findRightClass($node, $this->dispatcher->getMetadata());
    }
}
