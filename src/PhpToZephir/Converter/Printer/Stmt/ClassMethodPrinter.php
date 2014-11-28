<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr\Assign;
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
        $this->logger     = $logger;
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
        return "pStmt_ClassMethod";
    }

    /**
     * @param  Stmt\ClassMethod $node
     * @return string
     */
    public function convert(Stmt\ClassMethod $node)
    {
        $types = $this->typeFinder->getTypes(
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass(),
            $this->dispatcher->getMetadata()->getUse(),
            $this->dispatcher->getMetadata()->getClasses()
        );
        $this->dispatcher->setLastMethod($node->name);

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

        $stmt .= ")";
        $stmt .= $this->printReturn($node, $types);

        $stmt .= (null !== $node->stmts ? "\n{".$this->printVars($node, $varsInMethodSign).
             $this->dispatcher->pStmts($node->stmts)."\n}" : ';')."\n";

        return $stmt;
    }

    /**
     * @param  Stmt\ClassMethod $node
     * @param  array            $varsInMethodSign
     * @return string
     */
    private function printVars(Stmt\ClassMethod $node, array $varsInMethodSign)
    {
        $var = '';
        $vars  = array_diff(array_unique(array_filter($this->collectVars($node))), $varsInMethodSign);
        if (!empty($vars)) {
            $var .= "\n    var ".implode(', ', $vars).";\n";
        }

        return $var;
    }

    /**
     * @param  Stmt\ClassMethod $node
     * @param  array            $types
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
     * @param  Stmt\ClassMethod $nodes
     * @return boolean
     */
    private function hasReturnStatement($nodes)
    {
        foreach ($this->nodeFetcher->foreachNodes($nodes) as $node) {
            if ($node instanceof Stmt\Return_) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  Stmt\ClassMethod $node
     * @return array
     */
    private function collectVars($node)
    {
        $vars = array();
        if (is_array($node) === true) {
            $nodes = $node;
        } elseif (is_string($node) === false && method_exists($node, 'getIterator') === true) {
            $nodes = $node->getIterator();
        } else {
            return $vars;
        }

        foreach ($nodes as $stmt) {
            if ($stmt instanceof Expr\Assign) {
                if (($stmt->var instanceof Expr\PropertyFetch) === false) {
                    if (is_object($stmt->var->name) === false) { // if true it is a dynamic var
                        $vars[] = $stmt->var->name;
                    }
                }
            } elseif ($stmt instanceof Stmt\Foreach_) {
                if (null !== $stmt->keyVar) {
                    $vars[] = $stmt->keyVar->name;
                }
                $vars[] = $stmt->valueVar->name;
            } elseif ($stmt instanceof Stmt\If_) {
                if ($stmt->right instanceof Expr\Assign) {
                    $vars[] = $stmt->right->var->name;
                }

                if ($stmt->left instanceof Expr\Assign) {
                    $vars[] = $stmt->left->var->name;
                }
            } elseif ($stmt instanceof Stmt\Catch_) {
                $vars[] = $stmt->var;
            }

            $vars = array_merge($vars, $this->collectVars($stmt));
        }

        $vars = array_map(array($this->reservedWordReplacer, 'replace'), $vars);

        return $vars;
    }

    /**
     * @param  array      $type
     * @throws \Exception
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
