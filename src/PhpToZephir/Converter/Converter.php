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
     * @var NodeFetcher
     */
    private $nodeFetcher = null;

    /**
     * @param Dispatcher  $dispatcher
     * @param NodeFetcher $nodeFetcher
     */
    public function __construct(Dispatcher $dispatcher, NodeFetcher $nodeFetcher)
    {
        $this->dispatcher = $dispatcher;
        $this->nodeFetcher = $nodeFetcher;
    }

    /**
     * @param array          $stmts
     * @param ClassCollector $classCollector
     * @param Logger         $logger
     * @param string         $fileName
     * @param array          $classCollected
     *
     * @return array
     */
    public function nodeToZephir(array $stmts, ClassCollector $classCollector, Logger $logger, $fileName = null, array $classCollected = array())
    {
        $classInformation = ClassInformationFactory::getInstance();
        $metadata = $classInformation->getClassesMetdata($stmts);

        $this->implementsExist($metadata, $classCollector);

        return array(
            'code' => $this->dispatcher->convert($stmts, $metadata, $classCollector, $logger),
            'namespace' => $metadata->getNamespace(),
            'additionalClass' => $this->findAdditionalClasses($stmts, $logger),
        );
    }
    
    private function implementsExist(ClassMetadata $metadata, ClassCollector $classCollector)
    {
        foreach ($metadata->getImplements() as $implements) {
            $this->implementExist($metadata, $classCollector, $implements);
        }
    }
    
    private function implementExist(ClassMetadata $metadata, ClassCollector $classCollector, $implements)
    {
        // Class is in actual namespace
        if (array_key_exists($metadata->getNamespace() . '\\' . $implements, $classCollector->getCollected())) {
            return true;
        }
    
        foreach ($metadata->getClasses() as $use) {
            if (substr(strrchr($use, "\\"), 1) === $implements) {
                return true;
            }
        }
    
        throw new \Exception(sprintf('interface %s does not exist', $implements));
    }

    /**
     * @param array $stmts
     *
     * @return array
     */
    private function findAdditionalClasses(array $stmts, Logger $logger)
    {
        $closurePrinter = new ClosurePrinter($this->dispatcher, $logger);
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
