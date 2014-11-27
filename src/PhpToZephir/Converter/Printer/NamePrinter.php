<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Name;
use PhpToZephir\converter\Manipulator\ClassManipulator;

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
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ClassManipulator $classManipulator)
    {
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
        $this->classManipulator = $classManipulator;
    }

    public static function getType()
    {
        return "pName";
    }

    public function convert(Name $node)
    {
        $this->logger->trace(
            __METHOD__ . ' ' . __LINE__,
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
        );

        return $this->classManipulator->findRightClass($node, $this->dispatcher->getMetadata());
    }
}
