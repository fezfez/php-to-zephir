<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;

class YieldPrinter
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
        return "pExpr_Yield";
    }

    public function convert(Expr\Yield_ $node) {
        $this->logger->logNode('Yield does not exist in zephir', $node, $this->dispatcher->getMetadata()->getClass());

        if ($node->value === null) {
            return 'yield';
        } else {
            // this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
            return '(yield '
                 . ($node->key !== null ? $this->dispatcher->p($node->key) . ' => ' : '')
                 . $this->dispatcher->p($node->value)
                 . ')';
        }
    }
}
