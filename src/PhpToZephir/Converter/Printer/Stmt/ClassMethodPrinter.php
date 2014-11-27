<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use phpDocumentor\Reflection\DocBlock;
use PhpToZephir\ReservedWordReplacer;
use PhpToZephir\TypeFinder;

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
    private $lastMethod = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     */
    public function __construct(
        Dispatcher $dispatcher,
        Logger $logger,
        ReservedWordReplacer $reservedWordReplacer,
        TypeFinder $typeFinder
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->reservedWordReplacer = $reservedWordReplacer;
        $this->typeFinder = $typeFinder;
    }

    public function setLastMethod($value)
    {
        $this->lastMethod = $value;
    }
    public static function getType()
    {
        return "pStmt_ClassMethod";
    }

    public function convert(Stmt\ClassMethod $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
        $types = $this->typeFinder->getTypes(
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass(),
            $this->dispatcher->getMetadata()->getUse(),
            $this->dispatcher->getMetadata()->getClasses()
        );
        $this->dispatcher->setLastMethod($node->name);

        $stmt = $this->dispatcher->pModifiers($node->type) . 'function ' . ($node->byRef ? '&' : '') . $node->name . '(';
        $varsInMethodSign = array();

        if (isset($types['params']) === true) {
            $params = array();
            foreach ($types['params'] as $type) {
                $varsInMethodSign[] = $type['name'];
                $stringType = $this->printType($type);
                $params[] = ((!empty($stringType)) ? $stringType . ' ' : '') . '' . $type['name'] . ( ($type['default'] === null) ? '' : ' = ' . $this->dispatcher->p($type['default']));
            }

            $stmt .= implode(', ', $params);
        }

        $stmt .= ")";

        $hasReturn = $this->hasReturnStatement($node);
        if (array_key_exists('return', $types) === false && $this->hasReturnStatement($node) === false && $hasReturn === false) {
            $stmt .= ' -> void';
        } elseif(array_key_exists('return', $types) === true && empty($types['return']['type']['value']) === false) {
            $stmt .= ' -> ' . $this->printType($types['return']);
        }

        $var = '';
        $vars  = array_diff(array_unique(array_filter($this->collectVars($node))), $varsInMethodSign);
        if (!empty($vars)) {
            $var .= "\n    var " . implode(', ', $vars) . ";\n";
        }

        $stmt .= (null !== $node->stmts ? "\n{" . $var . $this->dispatcher->pStmts($node->stmts) . "\n}" : ';') . "\n";

        return $stmt;
    }

    private function hasReturnStatement($node)
    {
        $hasReturn = false;
        if (is_array($node) === true) {
            $nodes = $node;
        } elseif (is_string($node) === false && method_exists($node, 'getIterator') === true) {
            $nodes = $node->getIterator();
        } else {
            return $hasReturn;
        }

        foreach ($nodes as $stmt) {
            if ($stmt instanceof Stmt\Return_) {
                $hasReturn = true;
                return $hasReturn;
            }

            $hasReturn = $this->hasReturnStatement($stmt);
        }

        return $hasReturn;
    }

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
            }  elseif ($stmt instanceof Stmt\If_) {
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
        return ($type['type']['isClass'] === true) ? '<' . $type['type']['value'] . '>' : $type['type']['value'];
    }
}
