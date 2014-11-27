<?php

namespace PhpToZephir;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Scalar\MagicConst;

class ClassCollector
{
    /**
     * @var Logger
     */
    private $logger = null;
    /**
     * @var NodeFetcher
     */
    private $nodeFetcher = null;
    /**
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;

    /**
     * @param Logger $logger
     * @param NodeFetcher $nodeFetcher
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(Logger $logger, NodeFetcher $nodeFetcher, ReservedWordReplacer $reservedWordReplacer)
    {
        $this->logger               = $logger;
        $this->nodeFetcher          = $nodeFetcher;
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    /**
     * @param Node[] $stmts
     * @param unknown $fileName
     * @return string
     */
    public function collect(array $stmts, $fileName)
    {
        $namespace = null;
        $class = null;

        foreach ($this->nodeFetcher->foreachNodes($stmts) as $node) {
            if ($node instanceof Expr\Include_) {
                throw new \Exception('Include not supported in ' . $fileName . ' on line ' . $node->getLine());
            } elseif ($node instanceof Stmt\Goto_) {
                throw new \Exception('Goto not supported in ' . $fileName . ' on line ' . $node->getLine());
            } elseif ($node instanceof Stmt\InlineHTML) {
                throw new \Exception('InlineHTML not supported in ' . $fileName . ' on line ' . $node->getLine());
            } elseif ($node instanceof Stmt\HaltCompiler) {
                throw new \Exception('HaltCompiler not supported in ' . $fileName . ' on line ' . $node->getLine());
            } elseif ($node instanceof MagicConst\Trait_) {
                throw new \Exception('MagicConst\Trait_ not supported in ' . $fileName . ' on line ' . $node->getLine());
            } elseif ($node instanceof Stmt\Namespace_) {
                $namespace = implode('\\', $node->name->parts);
            } elseif ($node instanceof Stmt\Interface_ || $node instanceof Stmt\Class_) {
                if ($class !== null) {
                    throw new \Exception('Multiple class find in ' . $fileName);
                }
                $class = $namespace . '\\' .$this->reservedWordReplacer->replace($node->name);
            }
        }

        if ($namespace === null) {
            throw new \Exception('Namespace not found in ' . $fileName);
        }

        if ($class === null) {
            throw new \Exception('No class found in ' . $fileName);
        }

        return $class;
    }
}
