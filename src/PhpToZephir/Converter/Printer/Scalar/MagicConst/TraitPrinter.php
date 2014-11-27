<?php

namespace PhpToZephir\Converter\Printer\Scalar\MagicConst;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;

class TraitPrinter
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
        return "pScalar_MagicConst_Trait";
    }

    public function convert(MagicConst\Trait_ $node) {
        return '__TRAIT__';
    }
}
