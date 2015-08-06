<?php

namespace PhpToZephir\Converter;

class ClassMetadata
{
    /**
     * @var string
     */
    private $namespace = null;
    /**
     * @var string
     */
    private $class = null;
    /**
     * @var array
     */
    private $use = array();
    /**
     * @var array
     */
    private $classes = array();
    /**
     * @var array
     */
    private $classesAlias = array();
    /**
     * @var array
     */
    private $implements = array();

    /**
     * @param string $value
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }
    /**
     * @param string $value
     */
    public function setClass($value)
    {
        $this->class = $value;
    }
    /**
     * @param string $value
     */
    public function setImplements(array $value)
    {
        $this->implements = $value;
    }

    /**
     * @param \PhpParser\Node\Stmt\UseUse $value
     */
    public function addUse($value)
    {
        $this->use[] = $value;
    }
    /**
     * @param string $value
     */
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

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @return array
     */
    public function getImplements()
    {
        return $this->implements;
    }
    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }
    /**
     * @return array
     */
    public function getUse()
    {
        return $this->use;
    }
    /**
     * @return string
     */
    public function getClassesAlias()
    {
        return $this->classesAlias;
    }
    /**
     * @return string
     */
    public function getFullQualifiedNameClass()
    {
        return $this->namespace.'\\'.$this->class;
    }
}
