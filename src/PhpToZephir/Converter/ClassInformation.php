<?php

namespace PhpToZephir\Converter;

use PhpParser\Node\Stmt;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\NodeFetcher;

class ClassInformation
{
    /**
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;
    /**
     * @var NodeFetcher
     */
    private $nodeFetcher = null;

    /**
     * @param ReservedWordReplacer $reservedWordReplacer
     * @param NodeFetcher          $nodeFetcher
     */
    public function __construct(ReservedWordReplacer $reservedWordReplacer, NodeFetcher $nodeFetcher)
    {
        $this->reservedWordReplacer = $reservedWordReplacer;
        $this->nodeFetcher = $nodeFetcher;
    }

    /**
     * @param array $nodes
     *
     * @return \PhpToZephir\Converter\ClassMetadata
     */
    public function getClassesMetdata(array $nodes)
    {
        $classMetadata = new ClassMetadata();

        $classMetadata = $this->build($nodes, $classMetadata);

        $namespace = $classMetadata->getNamespace();

        if ($namespace === null) {
            throw new \Exception('Namespace not found');
        }

        return $classMetadata;
    }

    /**
     * @param array         $nodes
     * @param ClassMetadata $classMetadata
     *
     * @return ClassMetadata
     */
    public function build(array $nodes, ClassMetadata $classMetadata)
    {
        $class = null;
        foreach ($this->nodeFetcher->foreachNodes($nodes) as $nodeData) {
            $node = $nodeData['node'];
            if ($node instanceof Stmt\UseUse) {
                $classMetadata->addUse($node);
                $classMetadata->addClasses($this->reservedWordReplacer->replace(implode('\\', $node->name->parts)));
                if ($node->name->getLast() !== $node->alias) {
                    $classMetadata->addClassesAlias(
                        $node->alias,
                        $this->reservedWordReplacer->replace(implode('\\', $node->name->parts))
                    );
                }
            } elseif ($node instanceof Stmt\Namespace_) {
                $classMetadata->setNamespace(implode('\\', $node->name->parts));
            } elseif ($node instanceof Stmt\Interface_ || $node instanceof Stmt\Class_) {
                if ($class !== null) {
                    throw new \Exception('Multiple class find in '.$fileName);
                }
                $class = $this->reservedWordReplacer->replace($node->name);
                $classMetadata->setClass($class);

                if ($node->implements !== null) {
                	$implementsClean = array();
                	foreach ($node->implements as $implement) {
                		$implementsClean[] = $this->reservedWordReplacer->replace(implode('\\', $implement->parts));
                	}
                	$classMetadata->setImplements($implementsClean);
                }
            } elseif ($node instanceof Stmt\Interface_ || $node instanceof Stmt\Class_) {
                if ($class !== null) {
                    throw new \Exception('Multiple class find in '.$fileName);
                }
                $class = $this->reservedWordReplacer->replace($node->name);
                $classMetadata->setClass($class);
            }
        }

        return $classMetadata;
    }
}
