<?php

namespace PhpToZephir;

use phpDocumentor\Reflection\DocBlock\Tag\SeeTag;
use PhpParser\Node\Stmt\ClassMethod;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlock\Tag\ParamTag;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use phpDocumentor\Reflection\DocBlock\Tag\ThrowsTag;

class TypeFinder
{
    public function __construct()
    {

    }

    /**
     * @param ClassMethod $node
     */
    public function getTypes(ClassMethod $node, $actualClass, $definition = array())
    {
        $docBlock = $node->getAttributes()['comments'][0]->getText();
        $phpdoc = new DocBlock($docBlock);

        return $this->parseTags($phpdoc->getTags(), $actualClass, $definition, $node);
    }

    /**
     * @param array $tags
     * @throws \Exception
     */
    private function parseTags(array $tags, $actualClass, $definition, ClassMethod $node)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof SeeTag) {
                $definition = $this->parseSeeTag($tag, $actualClass, $definition, $node);
                break;
            } elseif ($tag instanceof ParamTag) {
                if (isset($definition['params']) === false) {
                    $definition['params'] = array();
                }

                $paramed = null;
                foreach ($node->params as $param) {
                    if ($param->name === str_replace('$', '', $tag->getVariableName())) {
                        $paramed = $param;
                    }
                }

                if ($paramed === null) {
                    throw new \Exception('not found');
                }

                $default = null;
                if ($paramed->default !== null) {
                    $defaultParamed = $paramed->default;
                    if (!empty($defaultParamed)) {
                        $name = $defaultParamed->name;
                        if (!empty($name)) {
                            $parts = $name->parts;
                            $default = $parts[0];
                        }
                    }
                }

                $definition['params'][] = array(
                    'name'    => $tag->getVariableName(),
                    'type'    => $this->findType($tag, $actualClass),
                    'default' => $default
                );
            } elseif ($tag instanceof ReturnTag && ($tag instanceof ThrowsTag) === false) {
                if (isset($definition['return']) === true) {
                    throw new \Exception(
                        sprintf('2 return  on "%s" method and "%s" class', $tag->getName(), $actualClass)
                    );
                }

                $definition['return'] = array(
                    'type' => $this->findType($tag, $actualClass)
                );
            } else {
                continue;
            }
        }

        return $definition;
    }

    /**
     * @param string $rawType
     * @return string
     */
    private function findType($tag, $actualClass)
    {
        // @TODO add ressource
        $type           = array();
        $rawType        = $tag->getType();
        $primitiveTypes = array(
            'string',
            'int',
            'integer',
            'float',
            'bool',
            'boolean',
            'array',
            'null',
            'callable'
        );

        if ($rawType === 'mixed') {
            return array('value' => '', 'isClass' => false);
        }

        $arrayOfPrimitiveTypes = array_map(function($val) { return $val . '[]'; }, $primitiveTypes);

        if (in_array($rawType, $primitiveTypes)) {
            $type = array('value' => $rawType, 'isClass' => false);
        } elseif (in_array($rawType, $arrayOfPrimitiveTypes)) {
            $type = array('value' => $rawType, 'isClass' => false);
        } else { // considered as class
            $type = array('value' => $this->searchClass($rawType, $actualClass), 'isClass' => true);
        }

        return $type;
    }

    /**
     * @param string $classReference
     * @param string $actualClass
     * @throws \Exception
     * @return unknown
     */
    private function searchClass($classReference, $actualClass)
    {
        $classReference   = str_replace('\\\\', '\\', $classReference);
        $actualClassAdded = substr($actualClass, 0, strrpos($actualClass, '\\')) . '\\' . $classReference;
        $actualClassAdded = str_replace('\\\\', '\\', $actualClassAdded);

        if ($this->loadClass($classReference) === true) {
            $fullClass = $classReference;
        } elseif ($this->loadClass($actualClassAdded) === true) {
            $fullClass = $actualClassAdded;
        } else {
            throw new \Exception(sprintf('Class "%s" and "%s" not found', $classReference, $actualClassAdded));
        }

        $fullClass = str_replace('\\\\', '\\', $fullClass);

        return '\\' . $fullClass;
    }
    /**
     * @param Tag $tag
     * @throws \Exception
     */
    private function parseSeeTag(Tag $tag, $actualClass, $definition, $node)
    {
        $classReference = strstr($tag->getReference(), '::', true);
        $methodRefrence = str_replace('::', '', strstr($tag->getReference(), '::'));
        $fullClass      = $this->searchClass($classReference, $actualClass);

        $rc = new \ReflectionClass($fullClass);
        if ($rc->hasMethod($methodRefrence) === false) {
            throw new \Exception('Method does not exist');
        }

        $phpdoc = new DocBlock($rc->getMethod($methodRefrence));

        return $this->parseTags($phpdoc->getTags(), $fullClass, $definition, $node);
    }

    /**
     * @param string $class
     * @return boolean
     */
    private function loadClass($class)
    {
        if (interface_exists ($class) === true) {
            return true;
        } elseif (class_exists($class) === true) {
            return true;
        } elseif (interface_exists ($class, true) === true) {
            return true;
        } elseif (class_exists($class, true) === true) {
            return true;
        }

        return false;
    }
}