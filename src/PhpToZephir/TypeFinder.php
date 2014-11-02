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
    public function getTypes(ClassMethod $node, $actualClass, array $use, $definition = array())
    {
        $attribute = $node->getAttributes();
        if (isset($attribute['comments']) === false) {
            return array();
        }
        
        $docBlock = $attribute['comments'][0]->getText();
        $phpdoc = new DocBlock($docBlock);

        return $this->parseTags($phpdoc->getTags(), $actualClass, $definition, $node, $use);
    }

    /**
     * @param array $tags
     * @throws \Exception
     */
    private function parseTags(array $tags, $actualClass, $definition, ClassMethod $node, $use)
    {
        foreach ($tags as $tag) {
            if ($tag instanceof SeeTag) {
                $definition = $this->parseSeeTag($tag, $actualClass, $definition, $node, $use);
                continue;
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
                    'type'    => $this->findType($tag, $actualClass, $use),
                    'default' => $default
                );
            } elseif ($this->isReturnTag($tag) === true) {
                if (isset($definition['return']) === true) {
                    continue;
                }

                $definition['return'] = array(
                    'type' => $this->findType($tag, $actualClass, $use)
                );
            } else {
                continue;
            }
        }

        return $definition;
    }

    /**
     * @param Tag $tag
     * @return boolean
     */
    private function isReturnTag(Tag $tag)
    {
        if ($tag instanceof ReturnTag && ($tag instanceof ThrowsTag) === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $rawType
     * @return string
     */
    private function findType($tag, $actualClass, $use)
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
            $type = array('value' => $this->searchClass($rawType, $actualClass, $use), 'isClass' => true);
        }

        return $type;
    }

    /**
     * @param string $classReference
     * @param string $actualClass
     * @throws \Exception
     * @return unknown
     */
    private function searchClass($classReference, $actualClass, $uses)
    {
        $classReference   = str_replace('\\\\', '\\', $classReference);
        $actualClassAdded = substr($actualClass, 0, strrpos($actualClass, '\\')) . '\\' . $classReference;
        $actualClassAdded = str_replace('\\\\', '\\', $actualClassAdded);
        $fullClass = null;
        $possibleClass = array($classReference, $actualClassAdded);

        if ($this->loadClass($classReference) === true) {
            $fullClass = $classReference;
        } elseif ($this->loadClass($actualClassAdded) === true) {
            $fullClass = $actualClassAdded;
        } else {
            // test the use
            foreach ($uses as $use) {
                /* @var $use \PhpParser\Node\Stmt\UseUse */

                // test alias
                if ($use->alias === str_replace('\\', '', $classReference)) {
                    $aliasClass = implode('\\', $use->name->parts);
                    $possibleClass[] = $aliasClass;
                    if ($this->loadClass($aliasClass) === true) {
                        $fullClass = $aliasClass;
                        break;
                    }
                }
            }

            if ($fullClass === null) {
                throw new \Exception(sprintf('Class "%s" not found refrenced in "%s"', implode(' or ', $possibleClass), $actualClass));
            }
        }

        $fullClass = str_replace('\\\\', '\\', $fullClass);

        return '\\' . $fullClass;
    }
    /**
     * @param Tag $tag
     * @throws \Exception
     */
    private function parseSeeTag(SeeTag $tag, $actualClass, $definition, $node, $use)
    {
        $classReference = strstr($tag->getReference(), '::', true);
        $methodRefrence = str_replace('::', '', strstr($tag->getReference(), '::'));
        $fullClass      = $this->searchClass($classReference, $actualClass, $use);

        $rc = new \ReflectionClass($fullClass);
        if ($rc->hasMethod($methodRefrence) === false) {
            throw new \Exception('Method does not exist');
        }

        $forceReturnType = false;
        foreach ($tag->getDocBlock()->getTags() as $childTag) {
            if ($this->isReturnTag($childTag) === true) {
                $forceReturnType = $childTag;
            }
        }

        $phpdoc = new DocBlock($rc->getMethod($methodRefrence));

        $definition = $this->parseTags($phpdoc->getTags(), $fullClass, $definition, $node, $use);

        if ($forceReturnType !== false) {
            $definition['return'] = array(
                'type' => $this->findType($forceReturnType, $actualClass, $use)
            );
        }

        return $definition;
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
