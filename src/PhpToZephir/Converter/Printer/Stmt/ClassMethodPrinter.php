<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\TypeFinder;
use PhpToZephir\NodeFetcher;

class ClassMethodPrinter
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
     * @var ReservedWordReplacer
     */
    private $reservedWordReplacer = null;
    /**
     * @var TypeFinder
     */
    private $typeFinder = null;
    /**
     * @var NodeFetcher
     */
    private $nodeFetcher = null;
    /**
     * @var string
     */
    private $lastMethod = null;

    /**
     * @param Dispatcher           $dispatcher
     * @param Logger               $logger
     * @param ReservedWordReplacer $reservedWordReplacer
     * @param TypeFinder           $typeFinder
     * @param NodeFetcher          $nodeFetcher
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ReservedWordReplacer $reservedWordReplacer,
        TypeFinder $typeFinder,
        NodeFetcher $nodeFetcher
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
        $this->typeFinder = $typeFinder;
        $this->nodeFetcher = $nodeFetcher;
    }

    /**
     * @param string $value
     */
    public function setLastMethod($value)
    {
        $this->lastMethod = $value;
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return 'pStmt_ClassMethod';
    }

    /**
     * @param Stmt\ClassMethod $node
     *
     * @return string
     */
    public function convert(Stmt\ClassMethod $node)
    {
        $types = $this->typeFinder->getTypes(
            $node,
            $this->dispatcher->getMetadata()
        );
        $this->dispatcher->setLastMethod($node->name);
        $node->stmts = $this->findModifiedToNonStaticVar($node->stmts);

        $stmt = $this->dispatcher->pModifiers($node->type).'function '.($node->byRef ? '&' : '').$node->name.'(';
        $varsInMethodSign = array();

        if (isset($types['params']) === true) {
            $params = array();
            foreach ($types['params'] as $type) {
                $varsInMethodSign[] = $type['name'];
                $stringType = $this->printType($type);
                $params[] = ((!empty($stringType)) ? $stringType.' ' : '').''.$type['name'].(($type['default'] === null) ? '' : ' = '.$this->dispatcher->p($type['default']));
            }

            $stmt .= implode(', ', $params);
        }

        $stmt .= ')';
        $stmt .= $this->printReturn($node, $types);

        $stmt .= (null !== $node->stmts ? "\n{".$this->printVars($node, $varsInMethodSign).
             $this->dispatcher->pStmts($node->stmts)."\n}" : ';')."\n";

        return $stmt;
    }

    /**
     * @param array|\ArrayIterator $node
     *
     * @return array|\ArrayIterator
     */
    private function findModifiedToNonStaticVar($node)
    {
        $noFetcher = new NodeFetcher();

        foreach ($noFetcher->foreachNodes($node) as &$stmt) {
            if ($stmt['node'] instanceof Expr\ClassConstFetch) {
                $isMovedToNonStatic = $this->dispatcher->isMovedToNonStaticVar($stmt['node']->name);
                if ($isMovedToNonStatic === true) {
                    $stmt['node'] = new Expr\Variable('this->'.$stmt['node']->name);
                }
            }
        }

        return $node;
    }

    /**
     * @param Stmt\ClassMethod $node
     * @param array            $varsInMethodSign
     *
     * @return string
     */
    private function printVars(Stmt\ClassMethod $node, array $varsInMethodSign)
    {
        $var = '';
        $vars = array_diff(array_unique(array_filter($this->collectVars($node))), $varsInMethodSign);
        if (!empty($vars)) {
            $var .= "\n    var ".implode(', ', $vars).";\n";
        }

        return $var;
    }

    /**
     * @param Stmt\ClassMethod $node
     * @param array            $types
     *
     * @return string
     */
    private function printReturn(Stmt\ClassMethod $node, array $types)
    {
        $stmt = '';
        if (array_key_exists('return', $types) === false && $this->hasReturnStatement($node) === false) {
            $stmt .= ' -> void';
        } elseif (array_key_exists('return', $types) === true && empty($types['return']['type']['value']) === false) {
            $stmt .= ' -> '.$this->printType($types['return']);
        }

        return $stmt;
    }

    /**
     * @param Stmt\ClassMethod $nodes
     *
     * @return bool
     */
    private function hasReturnStatement($nodes)
    {
        foreach ($this->nodeFetcher->foreachNodes($nodes) as $nodeData) {
            $node = $nodeData['node'];
            if ($node instanceof Stmt\Return_) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayIterator|array $node
     * @param array                $vars
     *
     * @return \ArrayIterator|array
     */
    private function collectVars($node, array $vars = array())
    {
        $noFetcher = new NodeFetcher();

        foreach ($noFetcher->foreachNodes($node) as &$stmt) {
            if ($stmt['node'] instanceof Expr\Assign) {
                if (($stmt['node']->var instanceof Expr\PropertyFetch) === false 
                 && ($stmt['node']->var instanceof Expr\StaticPropertyFetch) === false
                 && ($stmt['node']->var instanceof Expr\ArrayDimFetch) === false) {
                	if (is_object($stmt['node']->var->name) === false) { // if true it is a dynamic var
                        $vars[] = $stmt['node']->var->name;
                    }
                }
            } elseif ($stmt['node'] instanceof Stmt\Foreach_) {
                if (null !== $stmt['node']->keyVar) {
                    $vars[] = $stmt['node']->keyVar->name;
                }
                $vars[] = $stmt['node']->valueVar->name;
            } elseif ($stmt['node'] instanceof Stmt\For_) {
                foreach ($stmt['node']->init as $init) {
                    if ($init instanceof Expr\Assign) {
                        $vars[] = $init->var->name;
                    }
                }
            } elseif ($stmt['node'] instanceof Stmt\If_) {
                foreach ($this->nodeFetcher->foreachNodes($stmt['node']) as $nodeData) {
                    $node = $nodeData['node'];
                    if ($node instanceof Expr\Assign) {
                        $vars[] = $node->var->name;
                    } elseif ($node instanceof Expr\Array_) {
                        $vars[] = 'tmpArray'.md5(serialize($node->items));
                    }
                }
            } elseif ($stmt['node'] instanceof Stmt\Catch_) {
                $vars[] = $stmt['node']->var;
            } elseif ($stmt['node'] instanceof Stmt\Return_ && $stmt['node']->expr instanceof Expr\Array_) {
                $vars[] = 'tmpArray'.md5(serialize($stmt['node']->expr->items));
            }
        }

        $vars = array_map(array($this->reservedWordReplacer, 'replace'), $vars);

        return $vars;
    }

    /**
     * @param array $type
     *
     * @throws \Exception
     *
     * @return string
     */
    private function printType($type)
    {
        if (isset($type['type']) === false) {
            return '';
        }
        if (isset($type['type']['isClass']) === false) {
            throw new \Exception('isClass not found');
        }
        if (isset($type['type']['value']) === false) {
            throw new \Exception('value not found');
        }

        return ($type['type']['isClass'] === true) ? '<'.$type['type']['value'].'>' : $type['type']['value'];
    }
}
