<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;

class UnsetPrinter
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
        return "pExpr_Cast_Unset";
    }

    public function convert(Cast\Unset_ $node) {
        $this->logger->logNode('(unset) does not exist in zephir, remove cast', $node, $this->dispatcher->getMetadata()->getClass());
        return $this->dispatcher->pPrefixOp('Expr_Cast_Unset', '', $node->expr);
    }
}
