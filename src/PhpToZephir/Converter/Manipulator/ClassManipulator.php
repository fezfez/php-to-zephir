<?php

namespace PhpToZephir\converter\Manipulator;

use PhpParser\Node;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\Converter\ClassMetadata;

class ClassManipulator
{
    /**
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;

    /**
     * @param ReservedWordReplacer $reservedWordReplacer
     */
    public function __construct(ReservedWordReplacer $reservedWordReplacer)
    {
        $this->reservedWordReplacer = $reservedWordReplacer;
    }

    public function findRightClass(Node\Name $node, ClassMetadata $metadata, array $classCollected = array())
    {
        $class = implode('\\', $node->parts);
        $lastPartsClass = array_map(function ($value) { return substr(strrchr($value, '\\'), 1); }, $classCollected);

        $class = $this->reservedWordReplacer->replace($class);

        if (in_array($class, $classCollected)) {
            return '\\' . $class;
        } elseif (array_key_exists($class, $metadata->getClassesAlias())) {
            $alias = $metadata->getClassesAlias();
            $classKey = array_keys($alias, $class);
            return '\\' . $alias[$class];
        } elseif (false !== $key = array_search($class, $lastPartsClass)) {
            return '\\' . $this->classes[$key];
        } elseif (false !== $key = array_search($metadata->getNamespace() . '\\' . $class, $classCollected)) {
            return '\\' . $classCollected[$key];
        } else {
            return $class;
        }
    }
}
