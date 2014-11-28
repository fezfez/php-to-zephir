<?php

namespace PhpToZephir;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlock\Tag\ParamTag;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use phpDocumentor\Reflection\DocBlock\Tag\ThrowsTag;
use phpDocumentor\Reflection\DocBlock\Tag\SeeTag;

class TypeFinder
{
    /**
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;
    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @param ReservedWordReplacer $reservedWordReplacer
     * @param Logger               $logger
     */
    public function __construct(ReservedWordReplacer $reservedWordReplacer, Logger $logger)
    {
        $this->reservedWordReplacer = $reservedWordReplacer;
        $this->logger               = $logger;
    }

    /**
     * @param  ClassMethod $node
     * @param  string      $actualNamespace
     * @param  array       $use
     * @param  array       $classes
     * @param  array       $definition
     * @return array
     */
    private function parseParam(ClassMethod $node, $actualNamespace, array $use, array $classes, array $definition)
    {
        if (isset($definition['params']) === false) {
            $definition['params'] = array();
        }

        foreach ($node->params as $param) {
            $params = array();
            $params['name'] = $this->replaceReservedWords($param->name);
            $params['default'] = $param->default;
            $params['type'] = null;

          /* @var $param \PhpParser\Node\Param */
          if ($param->type === 'array') {
              $params['type']['value'] = 'array';
              $params['type']['isClass'] = false;
          } elseif ($param->type === null) { // scalar or not strong typed in method
             $docBlock = $this->nodeToDocBlock($node);
              if ($docBlock !== null) {
                  $params['type'] = $this->foundTypeInCommentForVar($docBlock, $param,  $actualNamespace, $use, $classes);
              }
          } elseif ($param->type instanceof \PhpParser\Node\Name) {
              $className = implode('\\', $param->type->parts);
              $params['type']['value'] = $this->searchClass($className, $actualNamespace, $use, $classes);
              $params['type']['isClass'] = true;
          }

            $definition['params'][] = $params;
        }

        return $definition;
    }

    /**
     * @param string $string
     */
    private function replaceReservedWords($string)
    {
        return $this->reservedWordReplacer->replace($string);
    }

    /**
     * @param  ClassMethod                             $node
     * @return NULL|\phpDocumentor\Reflection\DocBlock
     */
    private function nodeToDocBlock(ClassMethod $node)
    {
        $attribute = $node->getAttributes();

        if (isset($attribute['comments']) === false || isset($attribute['comments'][0]) === false) {
            return null;
        }

        $docBlock = $attribute['comments'][0]->getText();

        return new DocBlock($docBlock);
    }

    /**
     * @param  DocBlock   $phpdoc
     * @param  Param      $param
     * @param  string     $actualNamespace
     * @param  array      $use
     * @param  array      $classes
     * @return null|array
     */
    private function foundTypeInCommentForVar(DocBlock $phpdoc, Param $param, $actualNamespace, array $use, array $classes)
    {
        foreach ($phpdoc->getTags() as $tag) {
            if ($tag instanceof \phpDocumentor\Reflection\DocBlock\Tag\ParamTag) {
                if ($param->name === substr($tag->getVariableName(), 1)) {
                    return $this->findType($tag, $actualNamespace, $use, $classes);
                }
            }
        }

        foreach ($phpdoc->getTags() as $tag) {
            /* @var $tag \phpDocumentor\Reflection\DocBlock\Tag\VarTag */
           if ($tag instanceof \phpDocumentor\Reflection\DocBlock\Tag\SeeTag) {
               try {
                   $seeDocBlock = $this->findSeeDocBlock($tag, $actualNamespace, $use, $classes);

                   return $this->foundTypeInCommentForVar($seeDocBlock, $param, $actualNamespace, $use, $classes);
               } catch (\Exception $e) {
                   echo $e->getMessage()."\n";
               }
           }
        }

        return null;
    }

    /**
     * @param ClassMethod $node
     * @param string      $actualNamespace
     * @param array       $use
     * @param array       $classes
     * @param array       $definition
     * @retur narray
     */
    public function getTypes(ClassMethod $node, $actualNamespace, array $use, array $classes, array $definition = array())
    {
        $definition = $this->parseParam($node, $actualNamespace, $use, $classes, $definition);

        $phpdoc = $this->nodeToDocBlock($node);

        if ($phpdoc === null) {
            return $definition;
        }

        return $this->findReturnTag($phpdoc, $actualNamespace, $definition, $use, $classes);
    }

    /**
     * @param  string   $actualNamespace
     * @param  array    $definition
     * @param  array    $use
     * @param  array    $classes
     * @param  DocBlock $phpdoc
     * @return array
     */
    private function findReturnTag($phpdoc, $actualNamespace, array $definition, array $use, array $classes)
    {
        foreach ($phpdoc->getTags() as $tag) {
            if ($this->isReturnTag($tag) === true) {
                $definition['return'] = array(
                    'type' => $this->findType($tag, $actualNamespace, $use, $classes),
                );
                break;
            }
        }

        /* foreach ($phpdoc->getTags() as $tag) {
            /* @var $tag \phpDocumentor\Reflection\DocBlock\Tag\VarTag */
            /*if ($tag instanceof \phpDocumentor\Reflection\DocBlock\Tag\SeeTag) {
                $seeDocBlock = $this->findSeeDocBlock($tag, $actualNamespace, $use, $classes);
                return ($this->findReturnTag($seeDocBlock, $actualNamespace, $definition, $use, $classes));
            }
        }*/

        return $definition;
    }

    /**
     * @param  Tag     $tag
     * @return boolean
     */
    private function isReturnTag(Tag $tag)
    {
        if ($tag instanceof ReturnTag && ($tag instanceof ThrowsTag) === false && ($tag instanceof ParamTag) === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  string $actualNamespace
     * @param  Tag    $tag
     * @return string
     */
    private function findType(Tag $tag, $actualNamespace, array $use, array $classes)
    {
        $rawType = $tag->getType();

        if ($rawType === 'integer') {
            $rawType = 'int';
        }

        $primitiveTypes = array(
            'string',
            'int',
            'integer',
            'float',
            'double',
            'bool',
            'boolean',
            'array',
            'null',
            'callable',
            'scalar',
            'void',
            'object',
        );

        $excludedType = array('mixed', 'callable', 'callable[]', 'scalar', 'scalar[]', 'void', 'object', 'self', 'resource', 'true');

        if (in_array($rawType, $excludedType) === true || count(explode('|', $rawType)) !== 1) {
            return array('value' => '', 'isClass' => false);
        }

        $arrayOfPrimitiveTypes = array_map(function ($val) { return $val.'[]'; }, $primitiveTypes);

        if (preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $rawType) === 0) { // this is a typo
            $this->logger->log(sprintf('Type "%s" does not exist in docblock', $rawType));
            $type = array('value' => '', 'isClass' => false);
        } elseif (in_array(strtolower($rawType), $primitiveTypes)) {
            $type = array('value' => strtolower($rawType), 'isClass' => false);
        } elseif (in_array(strtolower($rawType), $arrayOfPrimitiveTypes)) {
            $type = array('value' => strtolower($rawType), 'isClass' => false);
        } else { // considered as class
            $type = array('value' => $this->searchClass($rawType, $actualNamespace, $use, $classes), 'isClass' => true);
        }

        return $type;
    }

    private function cleanClass($class)
    {
        $class = str_replace('\\\\', '\\', $class);

        if (substr($class, 0, 1) === '\\') {
            return substr($class, 1);
        } else {
            return $class;
        }
    }

    /**
     * @param  string     $classReference
     * @param  string     $actualNamespace
     * @throws \Exception
     * @return string
     */
    private function searchClass($classReference, $actualNamespace, array $uses, array $classes)
    {
        $classReference           = $this->cleanClass($classReference);
        $classWithActualNamespace = $this->cleanClass($actualNamespace.'\\'.$classReference);
        $fullClass                = null;
        $possibleClass            = array($classReference, $classWithActualNamespace);

        if ($this->loadClass($classReference) === true || in_array($classReference, $classes) === true) {
            $fullClass = $classReference;
        } elseif ($this->loadClass($classWithActualNamespace) === true || in_array($classWithActualNamespace, $classes) === true) {
            $fullClass = $classWithActualNamespace;
        } else {
            // test the use
            foreach ($uses as $use) {
                /* @var $use \PhpParser\Node\Stmt\UseUse */

                // test alias ex : test\myClass as mySuperClass ( mySuperClass === $classReference)
                if ($use->alias === str_replace('\\', '', $classReference)) {
                    $aliasClass = implode('\\', $use->name->parts);
                    $possibleClass[] = $aliasClass;
                    if ($this->loadClass($aliasClass) === true) {
                        $fullClass = $aliasClass;
                        break;
                    }
                } else {
                    // test alias ex : test\myClass as mySuperClass ( test\myClass\Test === $classReference)
                    $classParts = str_replace($use->alias, implode('\\', $use->name->parts), $classReference);
                    $possibleClass[] = $classParts;
                    if ($this->loadClass($classParts) === true) {
                        $fullClass = $classParts;
                        break;
                    }

                    $classParts = $this->rstrstr(implode('\\', $use->name->parts), '\\').'\\'.$classReference;
                    $possibleClass[] = $classParts;
                    if ($this->loadClass($classParts) === true) {
                        $fullClass = $classParts;
                        break;
                    }
                }
            }

            if ($fullClass === null) {
                throw new \Exception(sprintf('Class "%s" not found refrenced in "%s"', implode(' or ', $possibleClass), $actualNamespace));
            }
        }

        return '\\'.$this->cleanClass($fullClass);
    }

    /**
     * @param string $haystack
     * @param string $needle
     */
    private function rstrstr($haystack, $needle)
    {
        return substr($haystack, 0, strrpos($haystack, $needle));
    }
    /**
     * @param  Tag        $tag
     * @param  string     $actualNamespace
     * @throws \Exception
     */
    private function findSeeDocBlock(SeeTag $tag, $actualNamespace, array $use, array $classes)
    {
        $classReference = strstr($tag->getReference(), '::', true);
        $methodRefrence = str_replace(array(':', '(', ')'), '', strstr($tag->getReference(), '::'));
        $fullClass      = $this->searchClass($classReference, $actualNamespace, $use, $classes);

        $rc = new \ReflectionClass($fullClass);

        if ($rc->hasMethod($methodRefrence) === false) {
            throw new \Exception(sprintf('Method "%s" does not exist in "%s"', $methodRefrence, $fullClass));
        }

        return new DocBlock($rc->getMethod($methodRefrence)->getDocComment());
    }

    /**
     * @param  string  $class
     * @return boolean
     */
    private function loadClass($class)
    {
        if (interface_exists($class, false) === true) {
            return true;
        } elseif (class_exists($class, false) === true) {
            return true;
        } elseif (interface_exists($class, true) === true) {
            return true;
        } elseif (class_exists($class, true) === true) {
            return true;
        }

        return false;
    }
}
