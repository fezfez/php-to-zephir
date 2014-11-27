<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;

class ImplodePrinter
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
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
    }

    public static function getType()
    {
        return "pImplode";
    }

    public function convert(array $nodes, $glue = '')
    {
        $pNodes = array();
        foreach ($nodes as $node) {
            $pNodes[] = $this->dispatcher->p($node);
        }

        return implode($glue, $pNodes);
    }
}
