<?php

namespace PhpToZephir\Converter;

use PhpToZephir\Logger;

abstract class SimplePrinter
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher = null;
    /**
     * @var Logger
     */
    protected $logger = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger     $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }
}
