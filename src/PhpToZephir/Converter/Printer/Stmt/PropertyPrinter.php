<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Stmt;

class PropertyPrinter
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
        return "pStmt_Property";
    }

    public function convert(Stmt\Property $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        if ($node->props[0]->default !== null && $node->type === 9 || $node->type === 10 || $node->type === 12 ) {
            $this->logger->logNode("Static default attribute not supported in zephir, (see #188). ", $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
            $node->props[0]->default = null;
        }
        return $this->dispatcher->pModifiers($node->type) . $this->dispatcher->pCommaSeparated($node->props) . ';';
    }
}
