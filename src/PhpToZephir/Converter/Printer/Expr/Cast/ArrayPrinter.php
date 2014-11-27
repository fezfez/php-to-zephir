<?php

namespace PhpToZephir\Converter\Printer\Expr\Cast;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Array_;

class ArrayPrinter
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
        return "pExpr_Cast_Array";
    }

    public function convert(Cast\Array_ $node) {
        return $this->dispatcher->pPrefixOp('Expr_Cast_Array', '(array) ', $node->expr);
    }
}
