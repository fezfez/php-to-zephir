<?php

namespace PhpToZephir\Converter;

use PhpToZephir\Logger;
use PhpToZephir\NodeFetcher;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpToZephir\Converter\Printer\Expr\ClosurePrinter;
use PhpToZephir\ClassCollector;

class Converter
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
     * @var NodeFetcher
     */
    private $nodeFetcher = null;

    /**
     * @param Dispatcher  $dispatcher
     * @param Logger      $logger
     * @param NodeFetcher $nodeFetcher
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, NodeFetcher $nodeFetcher)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->nodeFetcher = $nodeFetcher;
    }

    /**
     * @param array  $stmts
     * @param string $fileName
     * @param array  $classCollected
     *
     * @return array
     */
    public function nodeToZephir(array $stmts, ClassCollector $classCollector, $fileName = null, array $classCollected = array())
    {
        $classInformation = ClassInformationFactory::getInstance();
        $metadata = $classInformation->getClassesMetdata($stmts);

        return array(
            'code' => $this->dispatcher->convert($stmts, $metadata, $classCollector),
            'namespace' => $metadata->getNamespace(),
            'additionalClass' => $this->findAdditionalClasses($stmts),
        );
    }

    /**
     * @param array $stmts
     *
     * @return array
     */
    private function findAdditionalClasses(array $stmts)
    {
        $closurePrinter = new ClosurePrinter($this->dispatcher, $this->logger);
        $lastMethod = null;
        $aditionalClass = array();
        $number = 0;

        foreach ($this->nodeFetcher->foreachNodes($stmts) as $nodeData) {
            $node = $nodeData['node'];
            if ($node instanceof Stmt\ClassMethod) {
                $lastMethod = $node->name;
            } elseif ($node instanceof Expr\Closure) {
                $aditionalClass[] = $closurePrinter->createClosureClass($node, $lastMethod, $number);
                ++$number;
            }
        }

        return $aditionalClass;
    }
}
