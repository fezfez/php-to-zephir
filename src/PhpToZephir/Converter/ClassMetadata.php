<?php

namespace PhpToZephir\Converter;

class ClassMetadata
{
    private $namespace = null;
    private $class = null;
    private $use = array();
    private $classes = array();
    private $classesAlias = array();

    /**
     * @param string $value
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }
    public function setClass($value)
    {
        $this->class = $value;
    }

    /**
     * @param \PhpParser\Node\Stmt\UseUse $value
     */
    public function addUse($value)
    {
        $this->use[] = $value;
    }
    public function addClasses($value)
    {
        $this->classes[] = $value;
    }

    /**
     * @param string $key
     */
    public function addClassesAlias($key, $value)
    {
        $this->classesAlias[$key] = $value;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
    public function getClass()
    {
        return $this->class;
    }
    public function getClasses()
    {
        return $this->classes;
    }
    public function getUse()
    {
        return $this->use;
    }
    public function getClassesAlias()
    {
        return $this->classesAlias;
    }
    public function getFullQualifiedNameClass()
    {
        return $this->namespace.'\\'.$this->class;
    }
}
